<?php
namespace IO\File;

class Loader extends \Core\Loader
{
    private $_extension;

    function getExtension()
    {
        return $this->_extension;
    }

    function setExtension( $ext )
    {
        $this->_extension = $ext;
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

    protected function load( $path, $args = null )
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