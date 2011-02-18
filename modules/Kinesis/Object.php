<?php
namespace Kinesis;

class Object extends Reference
{
    public $Data;

    private $id;

    function initialise()
    {
        if( is_null( $this->Parameter ) )
            $this->Parameter = new Field( null, new ObjectType( $this ) );

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
        //return $this->__express( __FUNCTION__, $arguments );
    }

    function __clone()
    {
        $this->Data = 'fuck';
        $this->__express( 'copy' );

        return 'yea';
    }
}