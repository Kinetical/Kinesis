<?php
namespace Kinesis\Parameter\Object;

class Method extends \Kinesis\Parameter
{
    function call( $name, array $arguments, &$ref )
    {
        try {
            return call_user_func_array( array( $ref, $name ), $arguments );
        } catch( Exception $e ) {
            return null;
        }
    }
}