<?php
namespace Util;

use Interfaces as I;

class Collection extends ArrayList
{
    function add( $value, $key = null )
    {
        if( is_null( $key ))
            $key = $this->count();

        $this->insert( $key, $value );
    }

    function insert( $key, $value )
    {
        if( $key == null
            && $value instanceof I\Nameable )
            $key = $value->getName();
        elseif( $key == null )
            $key = $this->count();

        $this->offsetSet( $key, $value );
    }

    function remove( $key )
    {
        $this->offsetUnset( $key );
    }

    function exists( $offset )
    {
        return $this->offsetExists( $offset );
    }

    function merge( array $data )
    {
        $this->Data += $data;
    }

    function clear()
    {
        $this->Data = array();
    }

    function values()
    {
        return array_values( $this->Data );
    }

    function keys()
    {
        return array_keys( $this->Data );
    }
}