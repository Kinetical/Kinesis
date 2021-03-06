<?php
namespace Kinesis;

class Dispatcher extends Task
{
    protected function execute( $args = null )
    {
        if( !is_array( $args ))
            return null;
        
        $tasks = $args[0];
        
        $result = '';
        if( is_array( $tasks ) &&
            count( $tasks ) > 0 )
        {
            foreach( $tasks as $statement )
            {
                if( is_callable( $statement ) )
                {
                    $val = $statement();
                    if( is_string( $val ))
                        $result .= $val;
                }
            }
        }

        return $result;
    }
}