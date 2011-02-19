<?php
namespace Kinesis;

class Object
{
    private $reference;


    private function integrity()
    {
        if( is_null( $this->reference ))
        {
            $this->reference = new Reference\Object( $this );
            Instantiator::initialise( $this->reference );
        }
    }

    function __construct()
    {
        $this->integrity();
    }

    function __get( $name )
    {
        $this->integrity();
        return $this->reference->__get( $name );
    }

    function __set( $name, $value )
    {
        $this->integrity();
        $this->reference->__set( $name, $value );
    }

    function __isset( $name )
    {
        $this->integrity();
        return $this->reference->__isset( $name );
    }

    function __unset( $name )
    {
        $this->__set( $name, null );
    }

    function __call( $method, array $arguments)
    {
        $this->integrity();
        return $this->reference->__call( $method, $arguments );
    }

    function __invoke()
    {
        $this->integrity();
        if( func_num_args() > 0 )
            $args = func_get_args();
        else
            $args = array();

        return call_user_func_array( $this->reference, $args );
    }

    function reference()
    {
        $this->integrity();
        return $this->reference;
    }
}