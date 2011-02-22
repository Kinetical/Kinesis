<?php
namespace Kinesis\Task;

class Builder extends Factory
{
    function execute()
    {
        $dispatcher = new \Kinesis\Dispatcher();
        return $dispatcher( $this->Children );
    }
}