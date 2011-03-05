<?php
namespace Kinesis\Reference;

class Object extends \Kinesis\Reference
{
    protected static $cache = array();
    protected static $objects = array();
    protected static $initialised = array();
    protected $id;

    function __construct( $obj, \Kinesis\Parameter $parameter = null )
    {
        if( is_null( $obj ))
            $obj = array();
        
        parent::__construct( $obj, $parameter );
    }

    function initialise()
    {
        if( is_object( $this->Container ))
        {
            $this->Container->Oid = $id = spl_object_hash( $this->Container );
            
            self::$cache[ $id ] = $this;
            self::$objects[ $id ] = $this->Container;
            $this->id = $id;

            $this->_initialise();
        }
    }

    private function _initialise()
    {
        if( method_exists( $this->Container, 'initialise' ) &&
           !array_key_exists( $this->id, self::$initialised ))
        {
            $this->Container->initialise();
            self::$initialised[ $this->id ] = true;
        }
    }

    public static function cache( $obj )
    {
        $id = array_search( $obj, self::$objects );
        if( !is_null( $id ))
            return self::$cache[ $id ];

        return null;
    }

    protected function overload( $method, array $args = null, $statement = null )
    {
        return parent::overload( $method, $args, $statement );
    }

    function __get( $name )
    {
        return $this->overload( __FUNCTION__, func_get_args() );
    }

    function __set( $name, $value )
    {
        $this->overload( __FUNCTION__, func_get_args() );
    }

    function __isset( $name )
    {
        return $this->overload( 'has', func_get_args() );
    }

    function __unset( $name )
    {
        $this->__set( $name, null );
    }

    function __call( $method, array $arguments)
    {
        return $this->overload( __FUNCTION__, array( $method, $arguments ) );
    }

    function  __toString()
    {
        return print_r( $this->Container, true );
    }

    function __clone()
    {
        $this->overload( 'copy' );
    }

    function __invoke()
    {
        return $this->overload( __FUNCTION__, func_get_args() );
    }
}