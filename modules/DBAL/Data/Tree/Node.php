<?php 
namespace DBAL\Data\Tree;

use \Util\Interfaces as I;

class Node extends \Util\Collection implements I\Attributable, I\Node, I\Nameable
{
    public $Name;
    public $Value;
    public $Attributes;
    
    private $_parent;
    private $_children;

    function __construct( $name = null, array $attributes = null, Node $parent = null )
    {
        parent::__construct();
        if( is_string( $name ))
            $this->setName( $name );
        
        if( $parent instanceof Node )
            $parent->_children->add( $this );

        if( is_array( $attributes ) )
            $this->setAttributes( $attributes );
        
    }

    function initialise()
    {
        $this->_children = new Node\Collection( $this );
        $this->Attributes = new \Util\Collection();
    }

    function getName()
    {
        return $this->Name;
    }

    function setName( $name )
    {
        $this->Name = $name;;
    }

    function getValue()
    {
        return $this->Value;
    }

    function setValue( $value )
    {
        $this->Value = $value;
    }

    function __toString()
    {
        return $this->getValue();
    }

    public function getIterator()
    {
        return new Node\Iterator( $this );
    }

    public function offsetExists($offset)
    {
        return $this->Attributes->offsetExists( $offset );
    }

    public function offsetGet($offset)
    {
        return  $this->Attributes->offsetGet( $offset );
    }

    public function offsetSet($offset, $value)
    {
        return $this->Attributes->offsetSet( $offset, $value );
    }

    public function offsetUnset($offset)
    {
        $this->Attributes->offsetUnset( $offset );
    }

    public function setAttributes( array $attributes )
    {
        $this->Attributes->merge( $attributes );
    }

    function getAttributes()
    {
        return $this->Attributes;
    }

    function setParent( $parent )
    {
        $this->_parent = $parent;
    }

    function getParent()
    {
        return $this->_parent;
    }

    function hasParent()
    {
        return ( $this->_parent !== null ) ? true : false;
    }

    function getChild( $index )
    {
        return $this->_children->offsetGet( $index );
    }

    function getChildren()
    {
        return $this->_children;
    }

    function hasChildren()
    {
        return ($this->_children->count() > 0)
                ? true
                : false;
    }

    function hasChild( $node, $recursive = false )
    {
        if( $this->hasChildren() )
            foreach( $this->_children as $child )
                if(   $child == $node
                   || $child->Oid == $node->Oid
                   || $child->hasChild( $node, $recursive ) )
                        return true;

        return false;
    }

    function hasAttribute( $name )
    {
        return $this->offsetExists( $name );
    }
    function hasAttributes()
    {
        if( $this->Attributes->count() > 0 )
                return true;

        return false;
    }

    function next()
    {
        if( !$this->isRoot() )
            return $this->getSibling( 1 );

        return false;
    }

    function getSibling( $shift )
    {
        if( $position = $this->hasChild( $this ) !== false
            && ( $child = $this->getParent()->getChild[ $position + $shift ] ) !== null )
                 return $child;

        return false;
    }

    function previous()
    {
        if( !$this->isRoot() )
            return $this->getSibling( -1 );

        return false;
    }

    function first()
    {
        if( $this->hasChildren() )
            return $this->getChild(0);
    }

    function last()
    {
        if( $this->hasChildren() )
            return $this->getChild( $this->count() -1 );
    }

    function count()
    {
        return $this->_children->count();
    }

    function setChildren( array $children )
    {
        $this->_children->merge( $children );
    }

    function isRoot()
    {
        return is_null($this->_parent) ? true : false;
    }

    function isLeaf()
    {
        return $this->_children->count() == 0 ? true : false;
    }

    function getRoot()
    {
        if( $this->isRoot() )
            return $this;
        else
            return $this->_parent->getRoot();
    }
}