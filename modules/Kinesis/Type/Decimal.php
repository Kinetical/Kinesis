<?php
namespace Kinesis\Type;

class Decimal extends Integer
{
    function toBase()
    {
        return 'decimal';
    }
    
    function toPrimitive( $value )
    {
        return floatval( $value );
    }
}
