<?php
namespace Kinesis\Task;

class Builder extends Factory
{
    protected function execute()
    {
        $tasks = $this->Children;
        
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