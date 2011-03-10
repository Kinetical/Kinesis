<?php
namespace Kinesis;

final class Type
{   
    private static $types = array();
    private static $parameters = array();
    private static $objects = array();

    static function initialise( $ref )
    {
        if( $ref instanceof Object )
            $ref = Reference\Object::cache( $ref );

        $typeName = self::resolve( $ref );
        $type = self::execute( $typeName );
        
        $ref->Type = $type;
        
        if( $ref instanceof Reference &&
            is_null( $ref->Parameter ) )
            $ref->Parameter = self::field( $typeName, $type );
    }
    
    protected static function resolve( $ref )
    {
        if( $ref instanceof Reference )
            $ref = $ref->Container;
        
        if( is_object( $ref ))
        {
            $name = get_class( $ref );
            self::$objects[$name] = $name;
            return self::$objects[$name];
        }
        
        return gettype( $ref );
    }
       
    protected static function execute( $name )
    {
        if( array_key_exists( $name, self::$types ))
            return self::$types[$name];
        
        if( array_key_exists($name, self::$objects ))
            $type = self::object( $name );
        else
            $type = self::scalar( $name );
            
        self::$types[$name] = $type;
        
        return $type;
    }

    private static function object( $ref )
    {
        if( is_string( $ref ))
            $name = $ref;
        else
            $name = get_class( $ref );
        
        if( stripos( $name, 'factory') !== false )
            $type = new Type\Object\Factory();
        elseif( stripos( $name, 'builder') !== false )
            $type = new Type\Object\Builder();
        elseif( stripos( $name, 'controller') !== false )
            $type = new Type\Object\Control();
        elseif( is_subclass_of($name, 'Kinesis\Task' ) )
            $type = new Type\Object\Task();
        elseif( is_subclass_of($name, 'Kinesis\ArrayList' ) )
            $type = new Type\Object\ArrayList();

        if( is_null( $type ))
            $type = new Type\Object();

        return $type;
    }

    private static function scalar( $ref )
    {
        // TODO: SCALAR TYPES
        if( is_string( $ref ))
            $name = $ref;
        else
            $name = gettype( $ref );
        
        switch( $name )
        {
            default:
                $type = new Type\ObjectType();
        }

        return $type;
    }

    static function field( $typeName, $type )
    {
        if( !array_key_exists( $typeName, self::$parameters ))
        {
            self::$parameters[ $typeName ] = new Parameter\Property( $typeName, $type );
            self::$parameters[ $typeName ]->assign();
        }

        return self::$parameters[ $typeName ];
    }

    static function all( Instantiator $instantiator )
    {
        return self::$types;
    }
}