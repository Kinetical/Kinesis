<?php
namespace IO\Serial;

class Writer extends \IO\Stream\Writer
{
    function writeObject( $object )
    {
        $this->write( serialize( $object ));
    }

    function getCallback()
    {
        return 'writeObject';
    }
}
