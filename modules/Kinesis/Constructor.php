<?php
namespace Kinesis;

class Constructor
{
    private static $types = array();

    function __call( $className, $args )
    {
        $class = $this->reflect( $className );

        return $class->newInstanceArgs( $args );
    }

    protected function reflect( $className )
    {
        if( !array_key_existS( $className, self::$types ))
             self::$types[$className] =  new \ReflectionClass( $className );

        return self::$types[$className];
    }
}
