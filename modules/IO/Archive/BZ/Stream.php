<?php
namespace IO\Archive\BZ;

class Stream extends \IO\File\Stream
{
    function open()
    {
        $this->pointer = bzopen( $this->resource->getPath(), (string)$this->mode );

        return $this->pointer;
    }

    function close()
    {
        bzclose( $this->pointer );
    }
}