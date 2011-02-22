<?php
namespace Kinesis\Task;

use Util\Interfaces as I;

abstract class Statement extends Node implements I\Nameable
{
    protected $name;
    
    function getQuery()
    {
        return $this->Parent->Parameters['Query'];
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