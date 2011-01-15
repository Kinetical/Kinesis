<?php
namespace IO\Stream;

abstract class Reader extends Wrapper
{
    abstract function read();
    
    function getCallback()
    {
        return 'read';
    }
}
