<?php
namespace Kinesis\Reference;

class ArrayList extends Object implements ArrayAccess
{
    function offsetGet( $name )
    {
        return $this->overload( __FUNCTION__, func_get_args() );
    }

    function offsetSet( $name, $value )
    {
        $this->overload( __FUNCTION__, func_get_args() );
    }

    function offsetExists( $name )
    {
        return $this->overload( 'has', func_get_args() );
    }

    function offsetUnset( $name )
    {
        $this->offsetSet( $name, null );
    }
}