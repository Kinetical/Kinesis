<?php
namespace Kinesis\Type;

class String extends Value
{
    public $Name = 'string';

    function getValue( Reference $value )
    {
        return (string)$value->Container;
    }
}
