<?php
namespace Core\Object;

class Cache extends \IO\Serial\Cache
{
    function add( $oid, \Core\Object $object )
    {
        if( is_string( $oid ))
            $object->Oid = $oid;
        if( is_null( $object->Oid ) )
            $object->Oid = \spl_object_hash( $object );

        return parent::add( $object->Oid, $object );
    }
}
