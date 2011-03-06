<?php
namespace Util;

use Util\Interfaces as I;

class Collection extends \Kinesis\ArrayList
{
    function add( $value, $key = null )
    {
        $this->insert( $key, $value );
    }

    function insert( $key = null, $value )
    {
        if( ( is_null( $key ) ||
              is_int( $key ) ) &&
            $value instanceof I\Nameable )
            $key = $value->getName();

        if( is_null( $key ) )
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
        array_walk( $data, array( $this, 'add'));
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
    
    function toArray()
    {
        return $this->Data;
    }
}