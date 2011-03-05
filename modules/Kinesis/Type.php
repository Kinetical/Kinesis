<?php
namespace Kinesis;

class Type
{
    private static $types = array();
    private static $parameters = array();

    function initialise( $ref )
    {
        if( $ref instanceof Object )
            $ref = Reference\Object::cache( $ref );

        $typeName = $this->resolve( $ref );
        $type = $this->execute( $typeName );
        
        $ref->Type = $type;
        
        if( $ref instanceof Reference &&
            is_null( $ref->Parameter ) )
            $ref->Parameter = $this->field( $typeName, $type, $ref );
    }
    
    protected function resolve( $ref )
    {
        if( $ref instanceof Reference )
            $ref = $ref->Container;
        
        if( is_object( $ref ))
            return get_class( $ref );
        
        return gettype( $ref );
    }
       
    protected function execute( $name )
    {
        if( array_key_exists( $name, self::$types ))
            return self::$types[$name];
        
        if( class_exists( $name ))
            $type = $this->object( $name );
        else
            $type = $this->scalar( $name );
            
        self::$types[$name] = $type;
        
        return $type;
    }

    private function object( $ref )
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
        elseif( is_subclass_of($name, 'Kinesis\Reference\ArrayList' ) )
        {
            $type = new Type\Object\ArrayList();
        }

        if( is_null( $type ))
            $type = new Type\Object();

        return $type;
    }

    private function scalar( $ref )
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

    function field( $typeName, $type, $ref )
    {
        if( !array_key_exists( $typeName, self::$parameters ))
        {
            self::$parameters[ $typeName ] = new Parameter\Field( $typeName, $type );
            self::$parameters[ $typeName ]->assign( $ref );
        }

        return self::$parameters[ $typeName ];
    }

    static function all( Instantiator $instantiator )
    {
        return self::$types;
    }
}