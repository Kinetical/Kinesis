<?php
namespace Kinesis\Parameter\Object;

class ArrayList extends \Kinesis\Parameter
{
    function get( $name, array $ref = array() )
    {
        return $ref[ $name ];
    }

    function set( $name, $value, array $ref = array() )
    {
        return $ref[$name] = $value;
    }

    function has( $name,  array $ref = array() )
    {

        return array_key_exists( $name, $ref )
               && !is_null( $ref[$name] );
    }

}
