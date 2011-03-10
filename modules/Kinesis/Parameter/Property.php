<?php
namespace Kinesis\Parameter;

class Property extends \Kinesis\Parameter
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
    
    function delegate( $ref, array $arguments = array() )
    {
        return function( $method, array $args = array() ) 
                   use ($ref, $arguments )
        {
            if( !empty( $arguments ) )
                $args = array_merge( $args, $arguments );
            
            $c = count( $args );
            if( $c < 5 )
            {
                switch( $c )
                {
                    case 0:
                        return $ref->$method();
                    case 1:
                        return $ref->$method( $args[0] );
                    case 2:
                        return $ref->$method( $args[0],
                                              $args[1] );
                    case 3:
                        return $ref->$method( $args[0],
                                              $args[1],
                                              $args[2] );
                    case 4:
                        return $ref->$method( $args[0],
                                              $args[1],
                                              $args[2],
                                              $args[3] );
                }
            }

            return call_user_func_array( array( $ref,
                                           $method ),
                                           $args );
        };
    }

    function listen( $method, $delegate )
    {
        if( !array_key_exists( $method, $this->listener ))
            $this->listener[ $method ] = array();

        $this->listener[$method][] = $delegate;
    }

    private function intercept( \Kinesis\Parameter $param )
    {
        $self = $this;
        $this->state( 
            function( $native, $method, array $arguments = array() ) 
                 use( $self, $param )
                    {
                        $delegate = $self->delegate( $native );
                        $result = $delegate( $method, $arguments );
                        if( is_null( $result ))
                        {
                            $delegate = $self->bypass( $native, $param );
                            $result = $delegate( $method, $arguments );
                        }
                        return $result;
                    }, 
                    $param );
    }
    
    

    private function bypass( \Kinesis\Parameter $param  )
    {
        $self = $this;
        $listeners = &$this->listener;
        
        $this->state( 
            function( $native, $method, array $arguments = array() ) 
                 use( $self, $param, &$listeners )
                    {
                        if( !array_key_exists( $method, $listeners ))
                            return null;
                        
                        if( $native instanceof \Kinesis\Reference )
                            $container = $native->Container;
                        else
                            $container = $native;
            
                        $delegate = $self->delegate( $param, array( $container ) );
                        
                        $param->Reference = $native;
                                
                        return $delegate( $method, $arguments );
                    }, 
                    $param );
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

    private function apply( $statement, array $intersect )
    {
        //TODO: CHECK IS_CALLABLE, EXCEPTION OTHERWISE
        foreach( $intersect as $intercede )
            $this->listen( $intercede, $statement );
    }

    protected function state( $statement, \Kinesis\Parameter $parameter = null )
    {
        if( is_null( $parameter ) && 
            $statement instanceof \Kinesis\Task\Statement )
            $parameter = $statement->Parameter;

        $class = get_class( $parameter );
        
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

    function assign()
    {
        static $c;
        $c++;
        $behaviors = $this->Type->roles();

        if( is_callable( $behaviors ))
            $behaviors = $behaviors();

        if( is_array( $behaviors ))
        {
            $defaultType = 'bypass';
            
            foreach( $behaviors as $type => $param )
            {
                if( is_int( $type ))
                    $type = $defaultType;
                
                $this->$type( $param );
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