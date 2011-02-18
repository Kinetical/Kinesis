<?php
namespace Kinesis\Type;

class ArrayList extends ValueType
{
    public $Name = 'Array';

    function getValue( Reference $value )
    {
        return (array)$value->Container[ $value->Parameter->Type->Name ];
    }
}
