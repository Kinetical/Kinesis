<?php
namespace IO\Text;

class Reader extends \IO\Stream\Reader
{
    function readLine()
    {
        $this->buffer = fgets( $this->stream->getPointer(), $this->stream->getLength() );

        return $this->buffer;
    }

    function readCharacter()
    {
        $this->buffer = fgetc( $this->stream->getPointer() );

        return $this->buffer;
    }
}
