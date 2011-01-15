<?php
namespace Core\Component;

class Loader extends \IO\File\PatternLoader
{
    function __construct( $path )
    {
        parent::__construct( $path, true );
    }
    function initialize()
    {
        parent::initialize();
        $this->getCache()->disable();
        $this->setExtension('php');
    }
    
    function match( $path )
    {
        // CLASSLOADER returns namespace path within modules component
        if( ($path = parent::match( $path )) !== false )
        {
            $cache = $this->getCache();
            $path = substr( $cache[ $path ] ,
                    strlen('modules'.DIRECTORY_SEPARATOR) );

            if( ($extPos = strpos( $path, $this->getExtension() )) !== false ) // STRIPS EXTENSION
                $path = substr( $path, 0, $extPos-1 );
        }

        return $path;
    }


}
