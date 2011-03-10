<?php
namespace Kinesis;

class Object extends Container
{
    public $Oid;
    
    function __get( $name )
    {
        return $this->reference->__get( $name );
    }

    function __set( $name, $value )
    {
        $this->reference->__set( $name, $value );
    }

    function __isset( $name )
    {
        return $this->reference->__isset( $name );
    }

    function __unset( $name )
    {
        $this->__set( $name, null );
    }

    function __call( $method, array $arguments)
    {
        return $this->reference->__call( $method, $arguments );
    }

    function __invoke()
    {
        if( func_num_args() > 0 )
            $args = func_get_args();
        else
            $args = array();

        return call_user_func_array( $this->reference, $args );
    }

    function reference()
    {
        if( is_null( $this->reference ) )
            $this->reference = new Reference\Object( $this );
        
        return $this->reference;
    }
}