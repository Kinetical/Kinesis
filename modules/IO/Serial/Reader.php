<?php
namespace IO\Serial;

class Reader extends \IO\Stream\Reader
{
    function readObject()
    {
        return unserialize( $this->readToEOF() );
    }

    function getCallback()
    {
        return 'readObject';
    }
}
