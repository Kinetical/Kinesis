<?php
namespace Kinesis\Parameter;

use Kinesis\Task\Statement as Statement;

class Field extends Property
{
    public $Expression;
    
    private $bypass = array();
    private $intercept = array();
    
    function getDefaultRoute()
    {
        return 'bypass';
    }
    
    function bypassed( $name )
    {
        return array_key_exists( $name, $this->bypass );
    }
    
    function intercepted( $name )
    {
        return array_key_exists( $name, $this->intercept );
    }

    function intercept( \Kinesis\Parameter $param )
    {
        
        $self = $this;
        $this->intercept += $this->state( 
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
    
    

    function bypass( \Kinesis\Parameter $param  )
    {
        $self = $this;
        $listeners = &$this->Listeners;
        
        $this->bypass += $this->state( 
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
    
    function __destruct()
    {
        unset( $this->Expression );
    }
}