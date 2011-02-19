<?php
namespace Kinesis\Parameter\Object;

class State extends Method
{
    function call( $name, array $arguments, &$ref )
    {
        $class = get_class( $ref );
        if( strtolower(substr( $name, 0, 3)) == 'has')
        {
            $prop = substr( $name, 3 );
            if( method_exists( $this->Reference, $name ))
                return $this->Reference->$name();
            elseif( isset( $this->Reference->$prop ) &&
                    !empty( $this->Reference->$prop ) )
                    return true;

            return false;
        }
        elseif( strtolower(substr( $name, 0, 2)) == 'is' )
        {
            $prop = substr( $name, 2 );
            if( method_exists( $this->Reference, $name ))
                return $this->Reference->$name();
            elseif( isset( $this->Reference->$prop ) &&
                    !empty( $this->Reference->$prop ) &&
                    $this->Reference->$prop !== false )
                    return true;

            return false;
        }

        return null;
    }
}