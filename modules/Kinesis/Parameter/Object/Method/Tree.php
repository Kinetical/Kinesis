<?php
namespace Kinesis\Parameter\Object\Method;

use Util\Interfaces as I;

class Tree extends Chain
{
    function call( $name, array $arguments, &$ref )
    {
        $object = $this->link( $name, $arguments, $ref );

        $class = $this->reflect();
        
        if( $class->hasProperty('Parent') )
        {
            if( !isset( $object->Parent ) )
                $object->Parent = $ref;
            elseif( $object->Parent !== $ref )
            {
                if( is_null( $property = $this->Name ))
                    $property = $name;
                
                array_pop( $ref->{$property} );
            }
        }
        
        return $this->Reference;
    }
}
