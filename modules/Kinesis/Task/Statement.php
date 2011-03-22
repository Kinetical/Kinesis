<?php
namespace Kinesis\Task;

use Util\Interfaces as I;

abstract class Statement extends Node implements I\Nameable
{
    protected $name;
    protected $component;
    
    function getComponent()
    {       
        return $this->component;
    }
    
    function setComponent( \Kinesis\Component $component )
    {
        $this->component = $component;
    }
    
    function getName()
    {
        return $this->name;
    }
    
    function setName( $name )
    {
        if( is_string( $name ))
            $this->name = $name;
    }
}