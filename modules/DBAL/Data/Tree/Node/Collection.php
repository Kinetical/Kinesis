<?php
namespace DBAL\Data\Tree\Node;

class Collection extends \Util\Collection\Dictionary
{
    private $_node;

    function __construct( \DBAL\Data\Tree\Node $node )
    {
        $this->_node = $node;

        parent::__construct(array(), 'DBAL\Data\Tree\Node');
    }

    function add( $node, $key = null )
    {
        $node->setParent( $this->_node );

        parent::add( $node, $key );
    }

    function remove( $index )
    {
        $subject = $this[$index];
        $subject->setParent( null );

        parent::remove( $index );
    }
}