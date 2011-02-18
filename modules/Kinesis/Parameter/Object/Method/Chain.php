<?php
namespace Kinesis\Parameter\Object\Method;

class Chain extends Factory
{
    function call( $name, array $arguments, &$ref )
    {
        $item = parent::call( $name, $arguments, $ref );

        $propName = $this->Name;
        if( is_null( $propName ))
            $propName = $name;

        $class = get_class( $ref );
        if( !property_exists( $class, $propName ) &&
            !is_array( $ref->{$propName} ))
            $ref->{$propName} = array();

            $ref->{$propName}[] = $item;

        return $this->Reference;
    }
}
