<?php
namespace Kinesis\Parameter\Object\Property;

class Method extends \Kinesis\Parameter\Object\Property
{
    function get( $name, $ref )
    {
        $m = 'get'.$name;
        if( method_exists( $ref, $m ) &&
            !is_null( $value = $ref->$m()))
            return $value;

        return parent::get( $name, $ref );
    }

    function set( $name, $value, &$ref  )
    {
        $m = 'set'.$name;
        if( method_exists( $ref, $m ) &&
            !is_null( $value = $ref->$m( $value )))
            return $value;

        return parent::set( $name, $value, $ref );
    }
}