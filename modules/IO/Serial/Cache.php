<?php
namespace IO\Serial;

class Cache extends \Core\Loader\Cache
{
    protected function changed( $path )
    {
        if( is_file( $cachedPath = $this->getCachePath( $path ))
            && is_file( $sourcePath = $this->getLoader()->parse($path))
            && filemtime( $cachedPath ) > filemtime($sourcePath))
            return false;

        return true;
    }

    function has( $path )
    {
        if( $this->changed( $path ))
            return false;
        
        $path = $this->getCachePath( $path );

        return is_file( $path );
    }

    private function getCacheStream( $path, $mode = 'r' )
    {
        return new \Core\Object\Stream( $this->getCachePath( $path ), $mode );
    }

    function load( $path )
    {
        $stream = $this->getCacheStream( $path );
        $stream->open();
        $value = $stream->readObject();
        $stream->close();

        return $value;
    }

    protected function getCachePath( $path )
    {
        $path = str_replace('/', '-', $path );
        //$path = md5( $path );
        return 'site'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$path.'.dat';
    }

    function save( $path, $value )
    {
        if( $value == null )
            return;


        $stream = $this->getCacheStream( $path , 'w+' );
        $stream->open();
        if( $value instanceof \Core\Object )
            $stream->writeObject( $value );
        else
            $stream->write( $value );

        $stream->close();
    }
}
