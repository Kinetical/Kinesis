<?php
namespace Core\Object;

class Reference
{
    public $ID;

    function __construct( $object )
    {
        if( is_string( $object ))
            $this->ID = $object;
        elseif( $object instanceof \Core\Object )
            $this->ID = $object->Oid;
    }
}