<?php
namespace IO\Archive\GZ;

class Writer extends \IO\Stream\Writer
{
    function write( $data )
    {
        $data = (string)$data;

        if( $this->stream instanceof \IO\Buffer\Stream )
            $length = $this->stream->getLength();
        else
            $length = strlen( $data );

        return gzwrite( $this->stream->getPointer(), $data, $length );
    }
}
