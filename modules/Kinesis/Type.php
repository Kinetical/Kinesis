<?php
namespace Kinesis;

class Type
{
    function __construct( $ref )
    {
        if( !is_null( $ref ))
            $ref->Type = $this;
    }
}