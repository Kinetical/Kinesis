<?php
namespace IO\File;

use Core\Interfaces as I;

class Loader extends \Core\Loader implements I\Nameable
{
    function getExtension()
    {
        return $this->parameters['extension'];
    }

    function setExtension( $ext )
    {
        $this->parameters['extension'] = $ext;
    }

    function setPath( $path )
    {
        $this->parameters['path'] = $path;
    }

    function getPath()
    {
        return $this->parameters['path'];
    }

    function getName()
    {
        return $this->getPath();
    }

    function setName( $idx )
    {
        $this->setPath( $idx );
    }

    
    protected function match( $path )
    {
        $cache = $this->getCache();
        return ($cache[ $path ] = $this->parse($path));
    }

    protected function parse( $path )
    {
        return $this->getPath().DIRECTORY_SEPARATOR.$path.'.'.$this->_extension;
    }

    protected function execute( $path, $args = null )
    {
        $cache = $this->getCache();
        //TODO: EXTRACT CONTEXT
        if( array_key_exists( $path, $cache ))
            require( $cache[ $path ] );

        //IF LOADED WITHOUT MANAGER CACHING
            try{ require( $this->parse($path) );
                 return true;
            } catch( \Exception $e ) {
                throw $e;
            }
    }
}