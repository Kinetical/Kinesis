<?php
namespace Kinesis\Reference;

class Object extends Base
{
    function initialise()
    {
        parent::initialise();
        
        if( is_object( $this->Container ))
            $this->Container->Oid = $this->id;
    }

    function __get( $name )
    {
        return $this->overload( __FUNCTION__, func_get_args() );
    }

    function __set( $name, $value )
    {
        $this->overload( __FUNCTION__, func_get_args() );
    }

    function __isset( $name )
    {
        return $this->overload( 'has', func_get_args() );
    }

    function __unset( $name )
    {
        $this->__set( $name, null );
    }

    function __call( $method, array $arguments)
    {
        return $this->overload( __FUNCTION__, array( $method, $arguments ) );
    }

    function  __toString()
    {
        return print_r( $this->Container, true );
    }

    function __clone()
    {
        $this->overload( 'copy' );
    }

    function __invoke()
    {
        return $this->overload( __FUNCTION__, func_get_args() );
    }
}