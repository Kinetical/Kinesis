<?php
namespace IO\Archive\GZ;

class Reader extends \IO\Stream\Reader
{
    function read()
    {
        if( $this->stream instanceof \IO\Buffer\Stream )
            $length = $this->stream->getLength();
        else
            $length = $this->stream->getFile()->getSize( true );

        return gzread( $this->stream->getPointer(), $length );
    }
}