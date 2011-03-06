<?php
namespace Kinesis;

abstract class Reference
{
    public $Parameter;
    public $Container;
    public $Type;

    private $_cache = array('statements' => array(), 'values' => array() );
    private $_expression;
    
    public static $all = array();

    function __construct( $obj, Parameter $parameter = null )
    {
        $this->Container = $obj;
        $this->Parameter = $parameter;
    }

    protected function overload( $method, array $args = null, $statement = null )
    {
        if( is_object( $args[0]))
            return null;
        
        $tname = $method.'_'.$args[0];

        self::$all[ $tname ] += 1;
        if( is_null( $this->_expression ))
            $this->_expression = $expression = new Task\Statement\Expression( $this, $method, $this->_cache );
        else
        {
            $expression = $this->_expression;
            $expression->Method = $method;
            $expression->Arguments = array();
        }

        if( is_null( $statement ) &&
            array_key_exists( $method, $this->_cache['statements'] ))
            $statement = $this->_cache['statements'][ $method ];

        return $expression( $statement, $args );
    }

    function __destruct()
    {
        unset( $this->Container );
        unset( $this->Type );
        unset( $this->Parameter );
    }
}