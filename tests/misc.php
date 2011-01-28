<?php

class Test implements ArrayAccess
{
    function offsetSet( $offset, $value )
    {

    }

    function offsetGet( $offset )
    {

    }

    function  offsetUnset($offset) {

    }

    function  offsetExists($offset) {

    }
}

$test = new Test();
var_dump( $test instanceof Traversable );