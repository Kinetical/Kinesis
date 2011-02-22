<?php
namespace Kinesis\Parameter\Object\Method;

use Util\Interfaces as I;

class Chain extends Factory
{
    protected function link( $name, array $arguments, &$ref )
    {
        $item = parent::call( $name, $arguments, $ref );

        if( is_null( $property = $this->Name ))
            $property = $name;

        $class = $this->reflect();
        if( !$class->hasProperty( $property ) ||
            !is_array( $ref->{$property} ))
            $ref->{$property} = array();

        if( $item instanceof I\Nameable )
            $ref->{$property}[$item->getName()] = $item;
        else
            $ref->{$property}[] = $item;
            
        return $item;
    }
    
    function call( $name, array $arguments, &$ref )
    {
        $this->link( $name, $arguments, $ref );

        return $this->Reference;
    }
}
