<?php
namespace DBAL\Query;

use \Util\Interfaces as I;

final class Builder extends \Core\Object
{
    private $_nodes;
    private $_query;

    function __construct( \DBAL\Query $query, array $params = array() )
    {
        $this->_nodes = array();
        $this->_query = $query;

        parent::__construct();

        $this->setParameters( $params );
    }

    function  initialize()
    {
        parent::initialize();

        $this->_parameters = new \Util\Collection();
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

    public function getParameters()
    {
        return $this->_query->getParameters();
    }
    function setParameters( array $params)
    {
        $this->_query->setParameters( $params );
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

    protected function execute( $stream = null )
    {
        $query = $this->_query;

        $text = (string)$this;

        if( method_exists( $query, 'setText'))
            $query->setText( $text );

        return $query( $stream );
    }

    function __invoke( $stream = null )
    {
        return $this->execute( $stream );
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