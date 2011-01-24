<?php
namespace IO\Archive\GZ;

class Stream extends \IO\File\Stream
{
    function __construct( $file = null, $mode = \IO\Stream\Mode::READ )
    {
        if( is_string( $file ) )
            $file = new File( $file );

        if( !( $file instanceof File ))
            throw new \IO\Exception('GZ\Stream may only open GZ\File');

        parent::__construct( $file, $mode );
    }
    
    function open()
    {
        $this->pointer = gzopen( $this->resource->getPath(), (string)$this->mode );

        return $this->pointer;
    }

    function close()
    {
        return gzclose( $this->pointer );
    }

    function eof()
    {
        return gzeof( $this->pointer );
    }

    function position()
    {
        return gztell( $this->pointer );
    }

    function seek($offset = 0, $whence = SEEK_SET)
    {
        return gzseek( $this->pointer, $offset, $whence );
    }
}
