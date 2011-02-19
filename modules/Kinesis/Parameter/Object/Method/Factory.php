<?php
namespace Kinesis\Parameter\Object\Method;

class Factory extends \Kinesis\Parameter
{
    function call( $name, array $arguments, &$ref )
    {
        $className = ucfirst($name);
        $classPath = $className;
        if( is_string( $ref->Namespace ))
            $classPath = $ref->Namespace.'\\'.$classPath;

        if( class_exists( $classPath ))
        {
            $refl = new \ReflectionClass( $classPath );
            
            $object = $refl->newInstanceArgs( $arguments );
            $object->Builder = $ref;

            return $object;
        }

        return null;
    }
}