<?php
namespace Kinesis;

class Object extends Reference
{
    private $id;

    function __construct( $obj, Parameter $parameter = null )
    {
        if( is_null( $obj ))
            $obj = array();
        parent::__construct( $obj, $parameter );
    }

    function initialise()
    {
        if( is_object( $this->Container ))
        {
            $this->id = spl_object_hash( $this->Container );
            if( method_exists( $this->Container, 'initialise' ) &&
                ( !isset( $this->Container->initialised ) ||
                   $this->Container->initialised == false ) )
            {

                $this->Container->initialise();
                $this->Container->initialised = true;
            }
        }
    }

    protected function __express( $method, array $args = null, $statement = null )
    {
        if( method_exists( $this->Container, 'initialise') &&
            ( $this->Container->initialised == false  ||
            $this->id !== spl_object_hash( $this->Container ) ))
            $this->initialise();

        return parent::__express( $method, $args, $statement );
    }

    function __get( $name )
    {
        return $this->__express( __FUNCTION__, func_get_args() );
    }

    function __set( $name, $value )
    {
        $this->__express( __FUNCTION__, func_get_args() );
    }

    function __isset( $name )
    {
        return $this->__express( 'has', func_get_args() );
    }

    function __unset( $name )
    {
        $this->__set( $name, null );
    }

    function __call( $method, array $arguments)
    {
        return $this->__express( __FUNCTION__, array( $method, $arguments ) );
    }

    function  __toString()
    {
        return print_r( $this->Container, true );
    }

    function __clone()
    {
        $this->__express( 'copy' );
    }
}