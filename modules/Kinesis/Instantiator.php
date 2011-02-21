<?php
namespace Kinesis;
/* NEW KEYWORD PASSTHRU ALTERNATIVE
 * THIS IS A STUB CLASS TO OVERCOME
 * THE SHORTCOMINGS OF PHP
 * RESIST THE URGE TO IMPLEMENT DYNAMIC TYPING
 * NOT MEANT TO BE FACTORY PATTERN
 * REPEAT: DO NOT MODIFY PROVIDED NAMESPACE RESOLUTION
 */
class Instantiator extends Constructor
{
    
    
    private static $types;
    
    private $_namespace;

    function __call( $class, array $args = null )
    {
        if( is_null( $this->_namespace ))
            $qualified = $class;
        else
            $qualified = $this->_namespace . '\\'. $class;

        $this->clear();

        $rClass = $this->reflect( $qualified );

        if( $rClass->isSubclassOf('Kinesis\Reference') ||
            $rClass->isSubclassOf('Kinesis\Object') )
            $instance = parent::__call( $qualified, $args );
        else
            $instance = new Reference\Object( parent::__call( $qualified, $args ) );

        if( !( $instance instanceof Object ))
            self::initialise( $instance, $class );

        return $instance;
    }

    static function initialise( $instance, $class )
    {
        if( array_key_exists( $class, self::$types ))
            $type = self::$types[ $class ];
        else
            $type = new Type( $class );

        $type->initialise( $instance );
        if( method_exists( $instance, 'initialise' ))
            $instance->initialise();
    }

    private function clear()
    {
        $this->_namespace = null;
        self::$types = Type::all( $this );
    }

    function __invoke( $namespace )
    {
        $this->_namespace = $namespace;

        return $this;
    }

    static function delegate()
    {
        return function( $namespace )
        {
            static $instant;
            if( is_null( $instant ))
                $instant = new Instantiator();

            return $instant( $namespace );
        };
    }
}