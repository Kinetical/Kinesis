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
//    public $Parameters;

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
//               elseif( is_array( $value ))
//               {
//                   $this->Parameters = $value;
//               }
            }
        }
        

        $this->id = \spl_object_hash( $this );

        self::$instances[ $this->id ] = $this->Name;
        self::$types[ $this->Name ] = $this;
    }

    function initialise( Reference $ref )
    {
        $type = $this->resolve( $ref );
        $ref->Type = $type;

        if( is_null( $this->field ))
            

        if( is_null( $ref->Parameter ) )
            $ref->Parameter = $this->field( $ref );

        $ref->Parameter->assign( $ref );

    }

    protected function resolve( $ref )
    {
        if( $ref instanceof Reference )
            $ref = $ref->Container;

        if( is_object( $ref ) )
        {
            return $this->resolveObject($ref);
        }

        return $this->resolveScalar( $ref );
    }

    private function resolveObject( $ref )
    {
        $name = get_class( $ref );

        if( stripos( $name, 'controller') !== false )
            $type = new Type\ControlType();

        if( is_null( $type ))
            $type = new Type\ObjectType();

        return $type;
    }

    private function resolveScalar( $ref )
    {
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
            self::$parameters[ $type ] = new Field( $this->Name, $ref->Type );

        return self::$parameters[ $type ];
    }

    static function all( Instantiator $instantiator )
    {
        return self::$types;
    }

}