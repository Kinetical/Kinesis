<?php
namespace DBAL\Query;

abstract class Node extends \DBAL\Data\Tree\Node
{
	private $_queryBuilder;
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
		return $this->_queryBuilder;
	}

	function getQuery()
	{
		return $this->_queryBuilder->getQuery();
	}

	function getModelNode()
	{
		if( $this->_modelNode == null )
			foreach( $this->_queryBuilder->Nodes as $node )
			{
				if( $node instanceof Model\Node )
				{
					$this->_modelNode = $node;
					break;
				}

			}

		return $this->_modelNode;
	}

	function getModel()
	{
            $modelNode = $this->getModelNode();

            if( $modelNode instanceof Model\Node )
                $model = $modelNode->getModel();

            return $model;
	}

	function setQueryBuilder( Builder $queryBuilder) {
		$this->_queryBuilder = $queryBuilder;
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