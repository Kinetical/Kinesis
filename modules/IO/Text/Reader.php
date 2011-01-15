<?php
namespace IO\Text;

class Reader extends \IO\File\Reader
{
    function readLine()
    {
        $this->buffer = fgets( $this->stream->getResource(), $this->stream->getBufferSize() );

        return $this->buffer;
    }

    function readCharacter()
    {
        $this->buffer = fgetc( $this->stream->getResource() );

        return $this->buffer;
    }
}
