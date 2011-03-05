<?php
namespace Kinesis\Parameter;

use Kinesis\Task\Statement as Statement;

class Field extends \Kinesis\Parameter
{
    private static $_intersection = array();
    private static $_replacements = array( 'get'      => '__get',
                                           'set'      => '__set',
                                           'call'     => '__call',
                                           'has'      => '__isset',
                                           'toString' => '__toString',
                                           'copy'     => '__clone',
                                           'invoke'   => '__invoke' );
    public $Expression;

    private $listener = array();

    function listen( $method, $delegate )
    {
        if( !array_key_exists( $method, $this->listener ))
            $this->listener[ $method ] = array();

        $this->listener[$method][] = $delegate;
    }

    function intercept( $native, \Kinesis\Parameter $param )
    {
        $this->state( new Statement\Delegate\Intercept( $native, $param ) );
    }

    function bypass( $native, \Kinesis\Parameter $param  )
    {
         $this->state( new Statement\Delegate\Bypass( $native, $param ) );
    }

    function build( $native, \Kinesis\Parameter $param )
    {
        if( $native instanceof \Kinesis\Reference )
            $native = $native->Container;
        
        return new \Kinesis\Reference\Object( $native, $param );
    }

    private function intersect( array $methods )
    {
        if( is_array( $methods ))
        {
            $intercede = array_intersect( array_values( $methods ), 
                                      array_keys( self::$_replacements ) );
            
//            $class = $methods;
//            $diff = array_diff( $class, self::$_replacements );
//            $classcede = array_intersect( array_values( $diff ), 
//                                      array_keys( array_flip( self::$_replacements ) ) );
        }

        return $intercede;
    }

    private function apply( \Kinesis\Task\Statement $statement, array $intersect )
    {
        foreach( $intersect as $intercede )
            $this->listen( $intercede, $statement );
    }

    protected function state( \Kinesis\Task\Statement $statement )
    {
        $class = get_class( $statement->Parameter );
        
        if( array_key_exists( $class, self::$_intersection ))
        {
            $intersect = self::$_intersection[ $class ];
        }
        else
        {
            $intersect = $this->intersect( get_class_methods( $class ) );
            self::$_intersection[ $class ] = $intersect;
        }

        if( !empty( $intersect ))
            $this->apply( $statement, $intersect );
    }

    function assign( $ref )
    {
        $behaviors = $this->Type->roles();

        if( is_callable( $behaviors ))
            $behaviors = $behaviors();

        if( is_array( $behaviors ))
        {
            $defaultType = 'bypass';
            
            foreach( $behaviors as $type => $param )
            {
                if( !is_string( $type ))
                    $type = $defaultType;
                
                $this->$type( $ref, $param );
            }
        }
    }

    function listeners( $method = null )
    {
        if( is_null( $method ))
            return $this->listeners;

         return $this->listener[ $method ];
    }

    function __destruct()
    {
        unset( $this->Expression );
    }
}