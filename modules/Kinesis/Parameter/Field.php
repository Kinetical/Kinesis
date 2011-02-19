<?php
namespace Kinesis\Parameter;

use Kinesis\Task\Statement as Statement;

class Field extends \Kinesis\Parameter
{
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
        if( !array_key_exists( $method, $this->listener ) ||
            !is_array( $this->listener[ $method ] ))
            $this->listener[ $method ] = array();

        $this->listener[$method][] = $delegate;
    }

    function intercept( $native, \Kinesis\Parameter $param )
    {
        $this->state( new Statement\Delegate\Intercept( $this->build( $native, $param ) ) );
    }

    function bypass( $native, \Kinesis\Parameter $param  )
    {
         $this->state( new Statement\Delegate\Bypass( $this->build( $native, $param ) ) );
    }

    function build( $native, \Kinesis\Parameter $param )
    {
        if( $native instanceof \Kinesis\Reference )
            $native = $native->Container;
        
        return new \Kinesis\Reference\Object( $native, $param );
    }

    private function intersect( \Kinesis\Task\Statement $statement, array $intercede  )
    {
        $flipped = array_flip( self::$_replacements );

        if( is_object( $statement->Reference->Container ))
        {
            $class = get_class_methods( get_class( $statement->Reference->Container ));
            $classcede = array_intersect( array_values( $class ), array_keys( $flipped ) );
            $ignored = array_intersect( $class, $classcede );

            if( count( array_intersect( $ignored , array_keys( $flipped ) ) ) > 0 )
            {
                foreach( $ignored as $meth )
                {
                    $key = array_search( $meth, $replace );
                    if( ($key = array_search( $key, $intercede )) !== false )
                        unset( $intercede[ $key ]);
                }
            }

        }

        return $intercede;
    }

    private function apply( \Kinesis\Task\Statement $statement, $intersect )
    {
        if( !empty( $intersect ))
        {
            while( !empty( $intersect ) )
            {
                $this->listen( array_pop( $intersect ), $statement );
            }
        }
//        else
//        {
//            $keys = ;
//            $this->listener = array_merge( $this->listener,
//                                            array_fill_keys($intercede,
//                                                            array( $statement ) ) );
//        }
    }

    protected function state( \Kinesis\Task\Statement $statement )
    {
        $methods = get_class_methods( $statement->Reference->Parameter );
        $intercede = array_intersect( array_values( $methods ), array_keys( self::$_replacements ) );

        $intersect = $this->intersect( $statement, $intercede );

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
            foreach( $behaviors as $type => $param )
            {
                if( !is_string( $type ))
                    $type = 'bypass';
                
                $this->$type( $ref->Container, $param );
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