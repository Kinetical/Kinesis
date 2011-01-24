<?php
namespace IO\File;

class Reader extends \IO\Stream\Reader
{
    function read()
    {
        if( $this->stream instanceof \IO\Buffer\Stream )
            $length = $this->stream->getLength();
        else
            $length = $this->stream->getFile()->getSize();

        $this->buffer = fread( $this->stream->getPointer(),
                               $length );

        return $this->buffer;
    }

    function readBlock( $blockSize = null )
    {
        if( !is_int( $blockSize ))
            $blockSize = $this->stream->getLength();

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
