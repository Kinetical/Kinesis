<?php
namespace Kinesis\Task\Statement;


class Expression
{
    public $Reference;
    public $Method;
    
    private $_method;

    function __construct( &$ref, $method, &$cache )
    {
        $this->Parameters['Source'] = &$cache;
        
        $this->Reference = &$ref;
        $this->Method = $method;
    }

    protected function getStatements()
    {
        if( $this->Reference->Parameter instanceof \Kinesis\Parameter )
            return $this->Reference->Parameter->Listeners[ $this->_method ];

        return null;
    }
    protected function recurse( array $args = array() )
    {
        $statement = $this->getStatements();

        if( is_array( $statement ))
        {
            foreach( $statement as $stmt )
            {
                
                if( !is_null( $value = $this( $stmt, $args ) ) )
                {
                    $this->Parameters['Source']['statements'][$args[0]] = $stmt;

                    return $value;
                }
            }
        }
        
        $this->Parameters['Source']['values'][ $args[0] ] = null;

        return null;
    }

    protected function bypassed( $name, $statement = null )
    {
        $method = $this->_method;
               
        if( $method == 'get' &&
            array_key_exists( $name, $this->Parameters['Source']['values'] ) )
            return true;

        return false;
    }

    protected function implemented( )
    {
        if(  is_object( $this->Reference->Container ) &&
             !($this->Reference->Container instanceof \Kinesis\Container) &&
             method_exists( $this->Reference->Container, $this->Method ) &&
           !($this->Reference->Container instanceof \Kinesis\Object ) )
            return true;

        return false;
    }

    function __invoke( $statement = null, array $args = array() )
    {
        if( strpos( $this->Method, '__' ) === 0 )
            $this->_method = str_replace('__','', $this->Method);
        
        if( strpos( $this->Method, 'offset') === 0 )
            $this->_method = strtolower( str_replace('offset','',$this->Method) );
        
        if( is_null( $statement ))
            return $this->recurse( $args );
        
        if( $this->bypassed( $args[0], $statement ))
            return $this->Parameters['Source']['values'][ $args[0] ];
        
        if( $this->implemented() )
            return call_user_func_array( $this->Reference->Container, $this->Method, $args );

        if( get_class( $statement ) == 'Closure' )
            $result = $statement( $this->Reference, $this->_method, $args );
        //else
            //TODO: THROW EXCEPTION: STATEMENT NOT VALID
        
        if( !empty( $args ) && 
            $this->Reference->Parameter->intercepted( $this->_method ) )
            if( !is_null( $result ) )
            {
                return $this->Parameters['Source']['values'][ $args[0] ] = $result;
            }

        return $result;
    }
}