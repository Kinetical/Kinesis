<?php
namespace IO\Stream;

abstract class Writer extends Handler
{
    function write( $data )
    {
        if( $this->wrapped() )
            $this->handler->write( $data );
    }
    
    function flush()
    {
        if( $this->buffered() )
        {
            $this->write( $this->buffer );
            $this->clear();
            
            return true;
        }

        return false;
    }

    function getCallback()
    {
        return 'write';
    }
}
