<?php
namespace Kinesis\Parameter\Object\Method;

use Util\Interfaces as I;

class ArrayList extends Control
{
    protected $Singular;

    function __construct( $name, $singular, $type = null )
    {
        $this->Singular = $singular;
        
        parent::__construct( $name, $type );
    }

    function call( $name, array $arguments, &$ref )
    {
       
        $type = substr( $name, - strlen( $this->Singular ) );
        if( $type !== $this->Singular )
            return null;

        $len = strlen( $name ) - strlen( $this->Singular );
        $name = substr( $name, 0, $len );

        if( $name == 'add' &&
            count( $arguments ) < 2 )
            $arguments[] = false;
        
        $ref = &$this->source( $ref );

        return parent::call( $name, $arguments, $ref );
    }

    function add( $value, $key = null, &$ref )
    {
        if( $key == -1 )
            unset( $key );
        return $this->insert( $key, $value, $ref );
    }

    function insert( $key = null, $value, &$ref )
    {
        if( ( is_null( $key ) ||
              is_int( $key ) ) &&
            $value instanceof I\Nameable )
            $key = $value->getName();

        if( empty( $key ) )
            $key = $this->count( $ref );

        $ref[ $key ] = $value;
        return true;
    }

    function remove( $key, &$ref )
    {
        unset( $ref[ $key ]);

        return true;
    }

    function has( $key, &$ref )
    {
        return array_key_exists( $key, $ref );

        return true;
    }

    function merge( array $data, &$ref )
    {
        array_walk( $data, array( $this, 'add'), $ref );
    }

    function clear( &$ref )
    {
        $ref = array();
    }

    function count( &$ref )
    {
        return count( $ref );
    }

    protected function &source( &$ref )
    {
        $src = &$ref->{$this->Name};
        return $src;
    }
}