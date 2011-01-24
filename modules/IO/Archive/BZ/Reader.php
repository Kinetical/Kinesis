<?php
namespace IO\Archive\BZ;

class Reader extends \IO\Stream\Reader
{
    function read()
    {
        return bzread( $this->stream->getPointer() );
    }
}
