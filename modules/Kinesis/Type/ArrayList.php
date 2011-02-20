<?php
namespace Kinesis\Type;

class ArrayList extends ValueType
{
    public $Name = 'Array';

    //TODO: ACCEPT \KNSS\Object ARGUMENT, RETRIEVE Reference FROM Expression
    function getValue( Reference $value )
    {

        return (array)$value->Container[ $value->Parameter->Type->Name ];
    }
}
