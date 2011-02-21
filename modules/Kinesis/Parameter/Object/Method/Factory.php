<?php
namespace Kinesis\Parameter\Object\Method;

class Factory extends \Kinesis\Parameter
{
    function call( $name, array $arguments, &$ref )
    {
        $className = ucfirst($name);
        $classPath = $className;
        if( array_key_exists( 'Namespace', $ref->Parameters ))
            $classPath = $ref->Parameters['Namespace'].'\\'.$classPath;

        if( class_exists( $classPath ))
        {
            $refl = new \ReflectionClass( $classPath );
            
            $arguments[] = $ref;
            
            $object = $refl->newInstanceArgs( $arguments );
            if( property_exists( get_class( $object ), 'Parent' ))
                $object->Parent = $ref;

            return $object;
        }

        return null;
    }
}