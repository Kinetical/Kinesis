<?php
namespace Kinesis\Task;

class Builder extends Node
{
    protected $component;
    
    function getComponent()
    {       
        return $this->component;
    }
    
    function setComponent( \Kinesis\Component $component )
    {
        $this->component = $component;
    }
    
    function execute()
    {
        $dispatcher = new \Kinesis\Dispatcher();
        return $dispatcher( $this->Children );
    }
}