<?php
namespace Kinesis\Type;

abstract class Value
{
    public $Name;

    abstract function getValue( Reference $value );
}
