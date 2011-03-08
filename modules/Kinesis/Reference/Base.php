<?php
namespace Kinesis\Reference;

class Base extends \Kinesis\Reference
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
        $id = spl_object_hash( $this->Container );
            
        self::$cache[ $id ] = $this;
        self::$objects[ $id ] = $this->Container;
        $this->id = $id;
        
        $this->_initialise();
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
}