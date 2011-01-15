<?php
namespace IO\File;

class PatternLoader extends Loader
{
    private $_directories = array();
    private $_recursive = true;

    function __construct( $path, $recursive = true )
    {
        $this->_recursive = $recursive;

        parent::__construct( $path );
    }

    function setRecursive( $recursive )
    {
        $this->_recursive = $recursive;
    }

    function isRecursive()
    {
        return $this->_recursive;
    }

    function addDirectory( $path )
    {
        $this->_directories[ $path ] = $path;
    }

    function getDirectories()
    {
        if( !count( $this->_directories ) )
            $this->loadDirectories();

        return $this->_directories;
    }

    private function loadDirectories()
    {
        $coreDirs = \Core::getInstance()->getDirectories();
        $path = $this->getPath();

        foreach( $coreDirs as $dir )
        {
            $match = substr( $dir, 0, strlen( $path ));

            if( $match === $path )
                $this->addDirectory( $dir );
        }
    }

    function match( $paths )
    {
        $cache = $this->getCache();

        if( !is_array( $paths ))
            $paths = array( $paths );

        foreach( $paths as $pattern )
            if( array_key_exists( $pattern, $cache ))
                return $pattern;

        foreach( $this->getDirectories() as $dir )
            foreach( $paths as $pattern )
            {
                $this->setPath( $dir );            
                if( is_file( $path = $this->parse( $pattern ) ))
                    return $cache[ $pattern ] = $path;
            }

        return false;
    }
}
