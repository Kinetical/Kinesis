<?php
namespace Kinesis\Parameter\Object\Method;

use Util\Interfaces as I;

class Node extends ArrayList
{
    function __construct( $type = null )
    {
        parent::__construct('Children','Child',$type);
    }

    function insert( $key = null, $value, &$ref )
    {
        parent::insert( $key, $value, $ref );

        if( is_object( $value ) &&
            property_exists( get_class( $value) , 'Parent' ))
            $value->Parent = $this->reference();

        return true;
    }

    function remove( $key, &$ref )
    {
        if( is_object( $value = $ref[ $key ] ) &&
            property_exists( get_class( $value) , 'Parent' ))
            unset( $value->Parent );

        unset( $ref[ $key ]);

        return true;
    }

    function clear( &$ref )
    {
        //TODO: LOOP THROUGH CHILDREN AND UNSET PARENT
        $ref = array();
    }

    function count( &$ref )
    {
        return count( $ref );
    }

    protected function reference()
    {
        return $this->Reference->Container;
    }
}