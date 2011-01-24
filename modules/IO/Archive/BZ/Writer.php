<?php
namespace IO\Archive\BZ;

class Writer extends \IO\Stream\Writer
{
    function write( $data )
    {
        $data = (string)$data;
        
        if( $this->stream instanceof \IO\Buffer\Stream )
            $length = $this->stream->getLength();
        else
            $length = strlen( $data );

        return bzwrite( $this->stream->getPointer(), $data, $length );
    }

    function flush()
    {
        return bzflush( $this->stream->getPointer() );
    }
}
