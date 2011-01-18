<?php
namespace IO\Text;

class Reader extends \IO\Stream\Reader
{
    function readLine()
    {
        $this->buffer = fgets( $this->stream->getPointer(), $this->stream->getBufferSize() );

        return $this->buffer;
    }

    function readCharacter()
    {
        $this->buffer = fgetc( $this->stream->getPointer() );

        return $this->buffer;
    }
}
