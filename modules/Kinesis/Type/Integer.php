<?php
namespace Kinesis\Type;

class Integer extends Value
{
    public $Name = 'int';

    function getValue( Reference $value )
    {
        return (int)$value->Container->{$value->Parameter->Type->Name};
    }
}
