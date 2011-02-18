<?php
namespace Kinesis\Parameter\Object;

class State extends Method
{
    function call( $name, array $arguments, &$ref )
    {
        $class = get_class( $ref );
        if( strtolower(substr( $name, 0, 3)) == 'has')
        {
            $name = substr( $name, 3 );
            return isset( $this->Reference->$name );
        }

        return null;
    }
}