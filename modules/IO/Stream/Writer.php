<?php
namespace IO\Stream;

abstract class Writer extends Wrapper
{
    abstract function write( $data );
    
    function flush()
    {
        if( $this->buffered() )
            $this->write( $this->buffer );

        $this->clear();
    }

    function getCallback()
    {
        return 'write';
    }
}
