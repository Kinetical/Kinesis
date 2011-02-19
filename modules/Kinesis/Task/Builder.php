<?php
namespace Kinesis\Task;

class Builder extends Factory
{
    public $Tasks = array();

    protected function execute()
    {
        $tasks = $this->Tasks;
        
        $result = '';
        if( is_array( $tasks ) &&
            count( $tasks ) > 0 )
        {
            foreach( $tasks as $statement )
            {
                if( $statement instanceof Statement )
                {
                    $val = $statement();
                    if( is_string( $val ))
                        $result .= $val;
                }
            }
        }

        return $result;
    }

    function clear()
    {
        $this->Tasks = array();
    }
}