<?php
namespace Kinesis\Parameter\Object;

class Property extends \Kinesis\Parameter
{
    function get( $name, &$ref )
    {
        if( !is_null( $value = $ref->$name ))
            return $value;

        return null;
    }

    function set( $name, $value, &$ref )
    {
        $ref->$name = $value;
    }

    function has( $name, &$ref )
    {
        return property_exists( get_class( $ref ), $name );
    }
}
