<?php
namespace IO\Stream;

abstract class Reader extends Handler
{
    function read()
    {
        if( $this->wrapped() )
            return $this->handler->read();

        return null;
    }
    
    function getCallback()
    {
        return 'read';
    }
}
