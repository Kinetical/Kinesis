<?php
namespace IO\File;

class Writer extends \IO\Stream\Writer
{
    function write( $value )
    {
        $value = (string)$value;

        return fwrite( $this->stream->getPointer(),
                       $value,
                       strlen($value) );
    }
}
