<?php
namespace Kinesis\Type;

class String extends Value
{   
    function toBase()
    {
        return 'string';
    }
    
    function toPrimitive( $value )
    {
        return (string)$value;
    }
    
    function getDefaultLength()
    {
        return 255;
    }
    
    function getDefaultValue()
    {
        return '';
    }
}
