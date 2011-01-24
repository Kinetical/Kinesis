<?php
namespace DBAL\Data\Tree\Node;

class Iterator extends \Util\Collection\Iterator implements \RecursiveIterator
{
    public function __construct( $node )
    {
        if( $node instanceof \DBAL\Data\Tree\Node )
            parent::__construct( $node->getChildren()->toArray() );
    }

    public function getChildren()
    {
        return new Iterator($this->current());
    }

    public function hasChildren()
    {
        return $this->current()->hasChildren();
    }
}