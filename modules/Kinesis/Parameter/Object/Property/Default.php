<?php
namespace Kinesis\Parameter\Object\Property;

class Defaults extends \Kinesis\Parameter
{
    function get( $name, &$ref )
    {
        $m = 'getDefault'.$name;
        if( is_null( $result ) &&
            method_exists( $ref, $m ))
            return $ref->$m();

        return null;
    }
}