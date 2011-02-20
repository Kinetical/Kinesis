<?php
namespace Kinesis\Parameter\Object;

class Method extends \Kinesis\Parameter
{
    function call( $name, array $arguments, &$ref )
    {
        $delegate = new \Kinesis\Task\Statement\Delegate( $ref, $name, $arguments );
        try {
            return $delegate();
        } catch( Exception $e ) {
            return null;
        }
    }
}