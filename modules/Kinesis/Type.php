<?php
namespace Kinesis;

class Type
{
    private static $types = array();
    private static $instances = array();
    private static $parameters = array();

    private $id;

    public $Name;
    public $Parent;

    function __construct()
    {
        if( func_num_args() > 0 )
        {
            $args = func_get_args();
            foreach( $args as $value )
            {
               if( is_string( $value ))
               {
                   if( array_key_exists( $value, self::$types ) )
                   {
                       $this->Parent = self::$types[ $value ];
                   }
                   else
                   {
                       $this->Name = $value;
                   }
               }
               elseif( is_object( $value ) )
               {
                   $this->Parent = $value;
               }
            }
        }
        

        $this->id = \spl_object_hash( $this );

        self::$instances[ $this->id ] = $this->Name;
        self::$types[ $this->Name ] = $this;
    }

    function initialise( $ref )
    {
        if( $ref instanceof Object )
            $ref = Reference\Object::cache( $ref );

        $type = $this->resolve( $ref );
        $ref->Type = $type;
        
        if( $ref instanceof Reference )
        {
            if( is_null( $ref->Parameter ) )
                $ref->Parameter = $this->field( $ref );

            $ref->Parameter->assign( $ref );
        }
    }

    protected function resolve( $ref )
    {      
        if( $ref instanceof Reference )
            $ref = $ref->Container;

        if( is_object( $ref ) )
            return $this->resolveObject($ref);

        return $this->resolveScalar( $ref );
    }

    private function resolveObject( $ref )
    {
        $name = get_class( $ref );

        if( stripos( $name, 'factory') !== false )
            $type = new Type\Object\Factory();
        elseif( stripos( $name, 'builder') !== false )
            $type = new Type\Object\Builder();
        elseif( stripos( $name, 'controller') !== false )
            $type = new Type\Object\Control();
        elseif( $ref instanceof Task )
            $type = new Type\Object\Task();

        if( is_null( $type ))
            $type = new Type\Object();

        return $type;
    }

    private function resolveScalar( $ref )
    {
        // TODO: SCALAR TYPES
        $name = gettype( $ref );
        switch( $name )
        {
            default:
                $type = new Type\ObjectType();
        }

        return $type;
    }

    function field( $ref )
    {
        $type = get_class( $ref->Type );

        if( !array_key_exists( $type, self::$parameters ))
            self::$parameters[ $type ] = new Parameter\Field( $this->Name, $ref->Type );

        return self::$parameters[ $type ];
    }

    static function all( Instantiator $instantiator )
    {
        return self::$types;
    }

}