<?php
namespace Kinesis\Type;

class Integer extends Value
{
    function toBase()
    {
        return 'integer';
    }
    
    function toPrimitive( $value )
    {
        return intval( $value );
    }
    
    function getDefaultLength()
    {
        return 10;
    }
    
    function getDefaultValue()
    {
        return 0;
    }
}
