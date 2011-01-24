<?php
namespace IO\File;

use \Util\Interfaces as I;

class Loader extends \Core\Loader implements I\Nameable
{
    function __construct(array $params = array(), Manager $manager = null)
    {
        if( !array_key_exists('CacheClass', $params ))
            $params['CacheClass'] = 'IO\File\Cache';
        
        parent::__construct( $params, $manager );
    }

    function getDefaultCache()
    {
        $params = array( 'DelegateTarget' => $this,
                         'DelegateMethod' => 'getPath' );

        $this->parameters['CacheParameters'] += $params;

        return parent::getDefaultCache();
    }

    function getExtension()
    {
        return $this->parameters['extension'];
    }

    function setExtension( $ext )
    {
        $this->parameters['extension'] = $ext;
    }

    function setRoot( $path )
    {
        $this->parameters['root'] = $path;
    }

    function getRoot()
    {
        return $this->parameters['root'];
    }

    function getName()
    {
        return $this->getRoot();
    }

    function setName( $name )
    {
        $this->setRoot( $name );
    }

    function getPath( $name )
    {
        return $this->getRoot().
                DIRECTORY_SEPARATOR.
                $name.
                '.'.$this->getExtension();
    }
    
    protected function parse( array $params = null )
    {
        $params['path'] = $this->getPath( $params['name'] );

        return $params;
    }

    protected function execute( array $params = null )
    {
        $path = $params['path'];
        //TODO: EXTRACT CONTEXT
        if( $this->cache->exists($path) )
            return false;

        //IF LOADED WITHOUT MANAGER CACHING
        try{ require( $path );
             return true;
        } catch( \Exception $e ) {
            throw $e;
        }
    }
}