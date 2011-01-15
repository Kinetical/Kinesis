<?php
namespace IO\Serial;

class Writer extends \IO\Stream\Writer
{
    function writeObject( \Core\Object $object )
    {
        $this->write( serialize( $object ));
    }

    function getCallback()
    {
        return 'writeObject';
    }
}
