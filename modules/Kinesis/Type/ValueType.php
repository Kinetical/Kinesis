<?php
namespace Kinesis\Type;

abstract class ValueType
{
    public $Name;

    abstract function getValue( Reference $value );
}
