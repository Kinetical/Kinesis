<?php
namespace Kinesis\Parameter\Object\Property;

class ArrayList extends \Kinesis\Parameter\Object\Property
{
    function __construct( $name = null, $type = null )
    {
        if( is_null( $name ))
            $name = 'Data';

        parent::__construct( $name, $type );
    }
    function get( $name, &$ref )
    {
        $prmName = $this->Name;

        if( !property_exists( $ref, $prmName ) ||
            !is_array( $ref->{$prmName} ) )
            $ref->{$prmName} = array();

        if( property_exists( $ref, $prmName ) &&
            is_array( $ref->$prmName ) &&
            array_key_exists( $name, $ref->$prmName ))
            return $ref->{$prmName}[$name];

        return parent::get( $name, $ref );
    }

    function set( $name, $value, &$ref  )
    {
        $prmName = $this->Name;
        
        if( $name instanceof \Util\Interfaces\Nameable )
            $name = $name->getName();

        if( !property_exists( $ref, $prmName ) ||
            !is_array( $ref->{$prmName} ) )
            $ref->{$prmName} = array();

        if( $name == $prmName &&
            is_array( $value ) )
            return $ref->{$prmName} = array_merge( $ref->{$prmName}, $value );
        else
            return $ref->{$prmName}[$name] = $value;

        return parent::set( $name, $ref );
    }

    function has( $name, &$ref )
    {
        if( is_null( $name ))
            return false;
        
        $prmName = $this->Name;
        if( property_exists( $ref, $prmName ) &&
            is_array( $ref->$prmName ) )
            return array_key_exists( $name, $ref->$prmName );

        return parent::has( $name, $ref );
    }
}
