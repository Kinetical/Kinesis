<?php
namespace Kinesis\Parameter\Object\Method;

class Factory extends \Kinesis\Parameter
{
    private $namespace;

    function __construct( $name = null, $type = null, $namespace = null )
    {
        $this->namespace = $namespace;
        parent::__construct( $name, $type );
    }
    function call( $name, array $arguments, &$ref )
    {
        $className = ucfirst($name);
        $classPath = $className;
        if( is_string( $this->namespace ))
            $classPath = $this->namespace.'\\'.$classpath;

        if( class_exists( $classPath )) //TODO: use Reflection->newInstanceArgs
            return new $classPath( $ref );

        return null;
    }
}