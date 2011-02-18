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
}