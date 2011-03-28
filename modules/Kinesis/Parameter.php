<?php
namespace Kinesis;

abstract class Parameter
{
    public $Name;
    public $Type; //OBJECT||VALUETYPE

    function __construct( $name = null, $type = null )
    {
        $this->Name = $name;
        $this->Type = $type;
    }
    
    function equals( Parameter $parameter )
    {
        if( $this->Name == $parameter->Name &&
            (string)$this->Type == (string)$parameter->Type )
            return true;
        
        return false;
    }
}