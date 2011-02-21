<?php
namespace IO\File;

class Stream extends \IO\Resource\Stream
{
    function __construct( $file = null, $mode = \IO\Stream\Mode::Read )
    {
        if( is_string( $file ) )
            $file = new \IO\File( $file );

        parent::__construct( $file, $mode );
    }

    function getFile()
    {
        return parent::getResource();
    }

    function setFile( \IO\File $file )
    {
        parent::setResource( $file );
    }

    function open()
    {
        $this->pointer = fopen( $this->resource->getPath(), (string)$this->mode );
        
        return $this->pointer;
    }

    function close()
    {
        return fclose( $this->pointer );
    }

    function eof()
    {
        return feof( $this->pointer );
    }

    function seek( $offset = 0, $whence = SEEK_SET )
    {
        return fseek( $this->pointer, $offset, $whence );
    }

    function rewind()
    {
        return rewind( $this->pointer );
    }

    function lock( $operation = LOCK_EX, &$wouldblock = null )
    {
        return flock( $this->pointer, $operation, $wouldblock );
    }

    function unlock()
    {
        return $this->lock( LOCK_UN );
    }

    function position()
    {
        return ftell( $this->pointer );
    }
}