<?php
namespace IO\Serial;

class Cache extends \Core\Loader\Cache
{
    function __construct( \Core\Loader $loader, $callback = null )
    {
        if( is_null( $callback ))
            $callback = array( $this, 'cache');
        
        parent::__construct( $loader, $callback );
    }
    protected function isModified( $path )
    {
        if( is_file( $cachedPath = $this->getCachePath( $path ))
            && filemtime( $cachedPath ) > filemtime($this->getLoader()->parse($path)) )
            return false;

        return true;
    }

    function has( $path )
    {
        if( parent::has( $path ))
            return $path;

        if( !$this->isModified( $path ) )
        {
            $this->add( $path, $this->get( $path ), false );
            return $path;
        }

        return false;
    }

    private function getCacheStream( $path, $mode = 'r' )
    {
        return new \Core\Object\Stream( $this->getCachePath( $path ), $mode );
    }

    function get( $path, $default = null )
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

    function cache( $path, $value )
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
