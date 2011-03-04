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

        list( $typeName, $type ) = $this->resolve( $ref );
              
        $ref->Type = $type;
        
        if( $ref instanceof Reference &&
            is_null( $ref->Parameter ) )
            $ref->Parameter = $this->field( $typeName, $type, $ref );
    }
    
    protected function resolve( $ref )
    {
        if( $ref instanceof Reference )
            $ref = $ref->Container;
        
        $object = false;
        $scalar = false;
        if( is_object( $ref ))
        {
            $name = get_class( $ref );
            $object = true;
            
        }
        elseif( is_scalar( $ref ))
        {
            $name = gettype( $ref );
            $scalar = true;
            
        }
        
        if( array_key_exists( $name, self::$types ))
            $type = self::$types[$name];
        
        if(is_null( $type ))
        {
            if( $object )
            {
                $type = $this->resolveObject( $name );
            }
            elseif( $scalar )
            {
                $type = $this->resolveScalar( $ref );
            }
            
            self::$types[$name] = $type;
        }
        
        return array( $name, $type );
    }

    private function resolveObject( $ref )
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

        if( is_null( $type ))
            $type = new Type\Object();

        return $type;
    }

    private function resolveScalar( $ref )
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