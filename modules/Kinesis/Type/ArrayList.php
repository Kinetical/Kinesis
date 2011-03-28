<?php
namespace Kinesis\Type;

class ArrayList extends Value
{   
    function toBase()
    {
        return 'array';
    }
    
    function toPrimitive( $value )
    {
        return (array)$value;
    }
    
    function getDefaultLength()
    {
        return null;
    }
    
    function getDefaultValue()
    {
        return array();
    }
}
