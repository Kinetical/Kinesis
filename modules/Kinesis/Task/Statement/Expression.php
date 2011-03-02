<?php
namespace Kinesis\Task\Statement;

class Expression extends Delegate
{
    private $_method;
    private $_statement;

    function __construct( &$ref, $method, &$cache )
    {
        
        $this->Parameters['Source'] = &$cache;

        parent::__construct( $ref, $method );
    }

    protected function getStatements()
    {
        if( $this->Reference->Parameter instanceof \Kinesis\Parameter\Field )
            return $this->Reference->Parameter->listeners( $this->_method );

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

    protected function isBypassed( $name, $statement = null )
    {
        $method = $this->_method;
        
        if( $statement instanceof Delegate\Intercept )
            return false;
        
        if( $method !== 'set' &&
            $method !== 'unset' &&
            $method !== 'isset' &&
            array_key_exists( $name, $this->Parameters['Source']['values'] ) )
            return true;

        return false;
    }

    protected function isImplemented( )
    {
        if(  is_object( $this->Reference->Container ) &&
             method_exists( $this->Reference->Container, $this->Method ) &&
           !($this->Reference->Container instanceof \Kinesis\Object ) )
            return true;

        return false;
    }

    function __invoke( $statement = null, array $args = array() )
    {
        $this->_method = str_replace('__','', $this->Method);
        
        $acc = count( $args );

        if( $acc > 0 &&
            $this->isBypassed( $args[0], $statement ))
            return $this->Parameters['Source']['values'][ $args[0] ];

        if( is_null( $statement ))
            return $this->recurse( $args );

        if( get_class( $statement ) == 'Closure' )
        {
            return call_user_func_array( $statement, $args );
        }
        
        

        if( !($statement instanceof \Kinesis\Task\Statement))
            return null;

        

        

        if( $this->isImplemented() )
        {
            $delegate = new Delegate( $this->Reference->Container, $this->Method, $args );
            return $delegate();
        }

        $this->Arguments = $args;
        $this->_statement = $statement;

        $result = $this->execute();
        
        if( $acc > 0 &&
            $statement instanceof Delegate\Bypass )
            if( !is_null( $result ) )
            {
                return $this->Parameters['Source']['values'][ $args[0] ] = $result;
            }
            
            

        return $result;
    }

    protected function execute()
    {
        $args = $this->Arguments;
        $args[] = $this->Reference->Container;

        $statement  = $this->_statement;

        if( $statement instanceof Delegate )
        {
            $statement->Method = $this->_method;
            $statement->Arguments =  $args;
            $statement->Parameters['Source'] = $this->Reference;

            return $statement();
        }
        elseif( is_callable( $statement ) )
            return $statement( $args );

        return null;
    }
}