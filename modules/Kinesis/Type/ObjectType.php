<?php
namespace Kinesis\Type;

use Kinesis\Parameter\Object as Object;

class ObjectType
{
    function roles( $roles = array() )
    {
        if( !is_array( $roles ))
            $roles = func_get_args();

        return array_merge( $roles,
                            array( new Object\Property(),
                                   new Object\Property\Method() )
                          );
    }
}
