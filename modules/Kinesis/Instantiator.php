<?php
namespace Kinesis;
/* NEW KEYWORD PASSTHRU ALTERNATIVE
 * THIS IS A STUB CLASS TO OVERCOME
 * THE SHORTCOMINGS OF PHP
 * RESIST THE URGE TO IMPLEMENT DYNAMIC TYPING
 * NOT MEANT TO BE FACTORY PATTERN
 * REPEAT: DO NOT MODIFY PROVIDED NAMESPACE RESOLUTION
 */
class Instantiator
{
    public $Types;
    
    private static $_namespace;
    static function __call( $class, array $args = null )
    {
        if( is_null( self::$_namespace ))
            $qualified = $class;
        else
            $qualified = self::$_namespace . '\\'. $class;

        self::$_namespace = null;

        $reflect = new \ReflectionClass( $qualified );
        if( $reflect->isSubclassOf('Kinesis\Reference') )
            return $reflect->newInstanceArgs( $args );

        return new \Kinesis\Object( $reflect->newInstanceArgs( $args ));
    }

    function __invoke( $namespace )
    {
        self::$_namespace = $namespace;

        return $this;
    }

    static function delegate()
    {
        return function( $namespace )
        {
            static $instant;
            if( is_null( $instance ))
                $instant = new Instantiator();

            return $instant( $namespace );
        };
    }
}