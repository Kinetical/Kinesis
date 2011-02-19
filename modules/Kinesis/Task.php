<?php
namespace Kinesis;

abstract class Task extends Object
{
    abstract protected function execute();

    function __invoke()
    {
        if( func_num_args() > 0 )
            $args = func_get_args();
        else
            $args = array();

        return $this->execute( $args );
    }

}