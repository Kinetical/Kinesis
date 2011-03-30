<?php
namespace Kinesis;

use Util\Interfaces as I;

abstract class Parameter implements I\Comparable
{
    public $Name;
    public $Type; //OBJECT||VALUETYPE

    function __construct( $name = null, $type = null )
    {
        $this->Name = $name;
        
        if( is_string( $type ))
            $type = Type::load( $type );
        
        $this->Type = $type;
    }
    
    function equals( $parameter )
    {
        if( $this->Name == $parameter->Name &&
            (string)$this->Type == (string)$parameter->Type )
            return true;
        
        return false;
    }
}