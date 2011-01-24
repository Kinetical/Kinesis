<?php
namespace IO\Buffer;

class Stream extends \IO\Stream
{
    protected $length = 1024;

    function getLength()
    {
        return $this->length;
    }

    function setLength( $length )
    {
        $this->length = $length;
    }
}
