<?php
namespace IO\File;

class Reader extends \IO\Stream\Reader
{
    function read()
    {
        $this->buffer = fread( $this->stream->getPointer(),
                               $this->stream->getBufferSize() );

        return $this->buffer;
    }

    function readBlock( $blockSize = null )
    {
        if( !is_int( $blockSize ))
            $blockSize = $this->stream->getBufferSize();

        $this->buffer = fread( $this->stream->getPointer(),
                               $blockSize );

        return $this->buffer;
    }

    function readToEOF()
    {
        $this->buffer = stream_get_contents( $this->stream->getPointer() );

        return $this->buffer;
    }
}
