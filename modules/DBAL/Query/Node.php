<?php
namespace DBAL\Query;

abstract class Node extends \DBAL\Data\Tree\Node
{
	protected $builder;
	private $_modelNode;
        private $_preBuilt = false;

	function __construct( Builder $queryBuilder, Node $parent = null )
	{
            parent::__construct( $parent );
		$this->setQueryBuilder( $queryBuilder );
		
	}

        function build()
        {
            try
            {
		$result  = $this->open();

		if( !$this->_preBuilt )
                    $result .= $this->openChildren ();

		$result .= $this->close();

		if( is_string( $result ))
			return $result;
	
            }
            catch( \Exception $e )
            {
                //echo $e->getMessage();
                throw new \Core\Exception();
            }

            return null;
        }

	function __toString()
	{
            return $this->build();
	}

        protected function openChildren()
        {
            if( $this->hasChildren() )
            {
			foreach( $children = $this->getChildren() as $child )
				$result .= (string)$child."\n";
            }

            $this->_preBuilt = true;

            return $result;
        }

	public function getQueryBuilder() {
		return $this->builder;
	}

	function getQuery()
	{
		return $this->builder->getQuery();
	}

	function getContainer()
	{
            return $this->getQuery()->container;
	}

	function getOwner()
	{
            $container = $this->getContainer();

            if( $container instanceof Node\Container )
                return $container->getOwner();

            return null;
	}

	function setQueryBuilder( Builder $queryBuilder) {
		$this->builder = $queryBuilder;
	}

	public function offsetSet($offset, $value) {
		parent::offsetSet( $offset, $value );
	}

	static function factory( $name, Builder $queryBuilder )
	{
            $type = \strtoupper($queryBuilder->getQuery()->getFormat());
		$className = ucfirst($name);
                $classPath = 'DBAL\\'.$type.'\\Query\\'.$className;

		if( class_exists( $classPath ))
			return new $classPath( $queryBuilder );

                
		throw new \Core\Exception( $name .' QueryItem does not exist('.$classPath.')' );
	}

	function create( $data = null )
	{
		return true;
	}

	function open()
	{
		return '';
	}

	function close()
	{
		return '';
	}
}