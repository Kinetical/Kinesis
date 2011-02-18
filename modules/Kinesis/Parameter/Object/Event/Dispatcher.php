<?php
namespace Kinesis\Parameter\Object\Event;

class Dispatcher extends \Kinesis\Parameter\Object\Method
{
    function call( $name, array $arguments, &$ref )
    {
        if( method_exists( $ref, $name ) )
        {
            $prioName = 'pre'.$name;
            if( method_exists( $ref, $prioName ) &&
                parent::call( $prioName, $arguments, $ref ) == false )
                return null;

            $results = parent::call( $name, $arguments, $ref );

            $postName = 'post'.$name;
            if( method_exists( $ref, $postName ) )
                return parent::call( $postName, $arguments, $ref );


        }
        if( !is_null( $results ))
            return $results;

        return null;
    }
}
