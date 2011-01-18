<?php
namespace DBAL\Query;

use \Core\Interfaces as I;

class Builder extends \Core\Object
{
	private $_nodes;
	private $_parameters;
	private $_query;

        function __construct( \DBAL\Query $query )
	{
            $this->_nodes = array();
            $this->_query = $query;

            parent::__construct();
	}

        function  initialize()
        {
            parent::initialize();

            $this->_parameters = new \Core\Collection();
        }

	public function getNodes()
        {
            if( $this->_nodes == null )
                    $this->_nodes = array();
            return $this->_nodes;
	}
	public function hasNode( $name )
	{
            return array_key_exists( $name, $this->_nodes ) ? true : false;
	}
	function setNodes($nodes)
        {
            $this->_nodes = $nodes;
	}

	public function addParameter( $param )
	{
            $type = get_class( $param );

            if( $param instanceof I\Nameable )
                $name = $param->getName();

            if( $name == null )
                    $this->_parameters[$type][] = $param;
            else
                    $this->_parameters[$type][$name][] = $param;
	}

	public function hasParameter( $type )
	{
            return array_key_exists( $type, $this->_parameters );
	}

	public function getParameters()
	{
            return $this->_parameters;
	}
	function setParameters( array $parameters)
	{
            $this->_parameters->merge($parameters);
	}

	function __toString()
	{
            foreach( $this->_nodes as $node )
                    $query .= $node->build();
            return $query;
	}

	function build()
	{
            return $this;
	}

	function execute( $connection = null )
	{
            $this->_query->setText( (string)$this );

            return $this->_query->execute( $connection );
	}

	function getQuery()
	{
            return $this->_query;
	}

	function setDataType( $type )
	{
            $this->_query->setDataType( $type );
	}

	function getDataType()
	{
            return $this->_query->getDataType();
	}
        
	function __call( $name, array $arguments )
	{
            $node = Node::factory( $name, $this );

            if( count( $arguments ) == 1 )
                    $arguments = $arguments[0];

            $node->setQueryBuilder( $this );
            if( $node->create( $arguments ) )
                if( array_key_exists( $name, $this->_nodes ))
                {
                    if( !is_array( $this->_nodes ))
                            $this->_nodes[$name] = array( $this->_nodes[$name] );

                    $this->_nodes[$name][] = $node;
                }
                else
                    $this->_nodes[$name] = $node;

            return $this;
	}
}