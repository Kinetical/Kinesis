<?php
namespace DBAL\Query;

use \Util\Interfaces as I;

final class Builder extends \Core\Object
{
    private $_nodes;
    protected $query;
    protected $container;

    function __construct( \DBAL\Query $query, array $params = array() )
    {
        $this->_nodes = array();
        $this->query = $query;

        parent::__construct();

        $this->setParameters( $params );
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

    function getContainer()
    {
        return $this->container;
    }

    public function getParameters()
    {
        return $this->query->getParameters();
    }
    function setParameters( array $params)
    {
        $this->query->setParameters( $params );
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
        $query = $this->query;

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
        return $this->query;
    }

    function setDataType( $type )
    {
        $this->query->setDataType( $type );
    }

    function getDataType()
    {
        return $this->query->getDataType();
    }

    function __call( $name, array $arguments )
    {
        $node = Node::factory( $name, $this );

        if( count( $arguments ) == 1 )
                $arguments = $arguments[0];

        $node->setQueryBuilder( $this );
        if( $created = $node->create( $arguments ) )
            if( array_key_exists( $name, $this->_nodes ))
            {
                if( !is_array( $this->_nodes ))
                        $this->_nodes[$name] = array( $this->_nodes[$name] );

                $this->_nodes[$name][] = $node;
            }
            else
                $this->_nodes[$name] = $node;

        if( $created &&
            $node instanceof Node\Container )
            $this->container = $node;

        return $this;
    }

    function getHandler()
    {
        return $this->query->getHandler();
    }

    function setHandler( \IO\Filter\Handler $handler )
    {
        $this->query->setHandler( $handler );
    }
}