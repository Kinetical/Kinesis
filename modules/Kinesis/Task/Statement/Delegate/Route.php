<?php
namespace Kinesis\Task\Statement\Delegate;

abstract class Route extends \Kinesis\Task\Statement\Delegate
{
    function execute()
    {
        if( func_num_args() > 0 )
            return parent::execute();
        
        $ref = $this->Reference;
        $this->Reference = $this->Reference->Parameter;
        $this->Reference->Reference = $this->Parameters['Source'];

        $value = parent::execute();
        $this->Reference = $ref;

        return $value;
    }
}
