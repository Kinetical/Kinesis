<?php
namespace Kinesis\Type;

class IntegerType extends ValueType
{
    public $Name = 'int';

    function getValue( Reference $value )
    {
        return (int)$value->Container->{$value->Parameter->Type->Name};
    }
}
