<?php
namespace IO\File;

use \Util\Interfaces as I;

abstract class Info implements I\Node, I\Nameable
{
    private $_path;
    private $_parent;
    private $_children;
    
    function __construct( $path, Info $parent = null )
    {
        $this->setPath( $path );
        if( $parent !== null )
            $this->setParent( $parent );
        
        //parent::__construct();
    }

    function initialise()
    {
        //parent::initialize();

        $this->_children = new \Util\Collection\Dictionary(array(),'\IO\File\Info');
    }

    function getPath()
    {
        return $this->_path;
    }

    function setPath( $path )
    {
        $this->_path = $path;
    }

    private function getPathInfo()
    {
        return pathinfo( $this->_path  );
    }

    function getName()
    {
        return $this->getPath();
    }

    function setName( $name )
    {
        $this->setPath( $name );
    }

    function getChildren()
    {
        return $this->_children;
    }
    function getParent()
    {
        return $this->_parent;
    }
    function isRoot()
    {
        return ( is_null( $this->_parent ))
                ? true
                : false;
    }
    function setParent( $parent )
    {
        $parent->getChildren()->add( $this );
        $this->_parent = $parent;
    }
    function setChildren( array $children )
    {
        $this->_children->merge( $children );
    }

    abstract function exists();
    abstract function delete();
}