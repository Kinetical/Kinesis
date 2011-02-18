<?php
namespace Kinesis\Type;

class StringType extends ValueType
{
    public $Name = 'string';

    function getValue( Reference $value )
    {
        return (string)$value->Container;
    }
}
