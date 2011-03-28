<?php
namespace Kinesis\Type;

abstract class Value
{
    protected $_name;

    function __construct()
    {
        $this->_name = $this->toBase();
    }

    function __toString()
    {
        return $this->_name;
    }

    abstract function toBase();
    abstract function toPrimitive( $value );
    abstract function getDefaultLength();
    abstract function getDefaultValue();
}
