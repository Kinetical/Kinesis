<?php 
namespace Core;

class Module extends Object 
{
    const Component = 'Component';
    const Action = 'Action';
    const Controller = 'Controller';
    
    private $_name;
    private $_path;
    private $_components;
    private $_loader;
    
    function __construct( $name )
    {
        $this->_name = $name;

        parent::__construct();        
    }
    
    function initialize()
    {
        parent::initialize();
        
        $this->_components = array();
        $this->setPath( 'modules'.DIRECTORY_SEPARATOR.$this->getName());
        //$this->setLoader( new \Core\Loader\ClassLoader( $this->getPath() ) );
    }

    function getLoader()
    {
        return $this->_loader;
    }

    function setLoader( Loader $loader )
    {
        $this->_loader = $loader;
    }

    function getName()
    {
        return $this->_name;
    }
    function setName( $name )
    {
        $this->_name = $name;
    }

    function getPath()
    {
        return $this->_path;
    }

    function setPath( $path )
    {
        $this->_path = $path;
    }

    function getDirectory()
    {
        return dirname( $this->getPath() );
    }

    function addComponent( \Core\Component $component )
    {
        return $this->_components[ $component->getName() ] = $component;
    }
    function removeComponent( $componentName )
    {
        unset( $this->_components[ $componentName ]);
    }

    function componentExists( $componentName, $type = Module::Component )
    {
        $path = $this->matchesComponent( $componentName, $type );

        return ( $path !== false ) ? true : false;
    }
    function hasComponent( $componentName, $type = Module::Component )
    {
        if( !array_key_exists( $componentName, $this->_components ) )
            return $this->componentExists( $componentName, $type );

        return array_key_exists( $componentName, $this->_components );
    }
    function getComponents()
    {
        return $this->_components;
    }
    function setComponents( array $components )
    {
        foreach( $components as $component )
            $this->addComponent( $component );
    }

    function loadComponent( $componentName, $type = Module::Component )
    {
        $componentPath = $this->matchesComponent( $componentName, $type );
        
        //TODO: CHECK FOR MISSING DEPENDANCEY,
        if( isset( $componentPath ))
            return $this->addComponent( new $componentPath( $componentName, $this ) );

        throw new \Core\Exception('Dependency mismatch.  Requested component not resolved.');
    }

    function matchesComponent( $componentName, $type = Module::Component )
    {
        return $this->getLoader()->match( $componentName.$type );
    }

    function hasAction( $actionName )
    {
        return $this->hasComponent( $actionName, Module::Action );
    }
    function hasController( $controllerName )
    {
        return $this->hasComponent( $controllerName, Module::Controller );
    }
}