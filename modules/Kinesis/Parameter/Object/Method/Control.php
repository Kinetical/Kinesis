<?php
namespace Kinesis\Parameter\Object\Method;

class Control extends \Kinesis\Parameter\Object\Method
{
    function call( $name, array $arguments, &$ref )
    {
        if( method_exists( get_class( $this ), $name ))
        {
            $arguments[] = &$ref;
            return parent::call( $name, $arguments, $this );
        }

        return null;
    }
}