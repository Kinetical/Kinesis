<?php
namespace Kinesis\Task;

class Builder extends Factory
{
    protected $Stream;

    function setStream( $stream )
    {
        $this->Stream = $stream;
    }
    function getStream()
    {
        return $this->Stream;
    }


//TODO: MAKE ABSTRACT
//    function getDefaultStream()
//    {
//        var_dump('ubernig');
//        return 'faggots';
//    }

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