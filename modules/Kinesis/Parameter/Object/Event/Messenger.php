<?php
namespace Kinesis\Parameter\Object\Event;

class Messenger extends \Kinesis\Paramter\Object\Method
{
    function call( $name, array $arguments, &$ref )
    {
        if( method_exists( $ref, $name ) )
        {
            $prioName = 'pre'.$name;
            if( method_exists( $ref, $prioName ) &&
                parent::call( $prioName, $arguments, $ref ) == false )
                return null;



            $evntName = 'on'.$name;
            if( method_exists( $ref, $evntName ) )
            {
                $name = ucfirst( $name );
                $handlers = $ref->$name;
                if( isset( $handlers ) &&
                    is_array( $handlers ))
                {
                    foreach( $handlers as $value )
                    {
                        $listener = $value['Listener'];
                        if( parent::call( $value['Method'], array( $ref, $arguments ) , $listener ) == false )
                            return null;
                    }
                }
                parent::call( $evntName, array( $arguments ) , $ref );
            }

            $results = parent::call( $name, $arguments, $ref );

            $postName = 'post'.$name;
            if( method_exists( $ref, $postName ) )
                parent::call( $postName, $arguments, $ref );


        }
        if( !is_null( $results ))
            return $results;

        return null;
    }
}