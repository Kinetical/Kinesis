<?php
namespace Core;

use Core\Interfaces as I;

abstract class Loader extends Filter implements I\Nameable
{
    private $_path;
    private $_manager;
    private $_cache;

    function __construct( $path, Manager $manager = null )
    {
        $this->_path = $path;
        $this->_manager = $manager;

        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();

        if( !$this->caching() )
            if( $this->managed() )
                $this->setCache( $this->_manager->getCache() );
            else
                $this->setCache( new \Core\Loader\Cache( $this ) );

        if( !$this->Type->hasEvents( array('parse', 'match')))
        {
            $this->Type->addEvent( new \Core\Event('parse') );
            $this->Type->addEvent( new \Core\Event('match') );
        }
    }

    protected function caching()
    {
        return ( !is_null( $this->_cache )
                 && $this->_cache->enabled() );
    }

    function getCache()
    { 
        return $this->_cache;
    }

    function setCache( Cache $cache )
    {
        $this->_cache = $cache;
    }

    function managed()
    {
        return ($this->_manager instanceof Manager ) 
               ? true
               : false;
    }

    function getManager()
    {
        return $this->_manager;
    }

    function setManager( Manager $manager )
    {
        $this->_manager = $manager;
    }

    function setPath( $path )
    {
        $this->_path = $path;
    }

    function getPath()
    {
        return $this->_path;
    }

    function getName()
    {
        return $this->getPath();
    }
    
    function setName( $idx )
    {
        $this->setPath( $idx );
    }

    protected function parse( $path )
    {
        return true;
    }
    abstract protected function match( $path );
    abstract protected function execute( $path, $args = null );
}
