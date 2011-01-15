<?php

class Object
{
    protected $property = array();

    function __construct( array $property = null )
    {
        if( is_array( $property ) )
            $this->property = $property;
    }

    function __get( $name )
    {
        return $this->property[ $name ];
    }

    function __set( $name, $value )
    {
        $this->property[ $name ] = $value;
    }

    function __isset( $name )
    {
        return array_key_exists( $name, $this->property );
    }

    function  __unset( $name )
    {
        unset( $this->property[ $name ] );
    }
}