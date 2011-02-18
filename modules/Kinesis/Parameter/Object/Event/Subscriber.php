<?php
namespace Kinesis\Parameter\Object\Event;

class Subscriber extends \Kinesis\Parameter
{
    function get( $name, &$ref )
    {
        $evntName = 'on'.$name;
        if( method_exists( $ref, $name ))
        {
            if( is_null( $ref->$name ) ||
                !is_array( $ref->$name ) )
                 $ref->$name = array();

            return $ref->$name;
        }

        return null;
    }

    function set( $name, $value, &$ref )
    {
        $evntName = 'on'.$name;
        if( method_exists( $ref, $name ))
        {
            if( is_null( $ref->$name ) ||
                !is_array( $ref->$name ) )
                $ref->$name = array();

            array_push( $ref->$name, (array)$value );
            return $ref->$name;
        }

        return null;
    }
}
