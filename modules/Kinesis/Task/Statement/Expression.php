<?php
namespace Kinesis\Task\Statement;

class Expression extends Delegate
{
    private $_method;
    private $_statement;

    function __construct( &$ref, $method, &$cache )
    {
        $this->_method = str_replace('__','', $method);
        $this->Source = &$cache;

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
            $statement = array_reverse( $statement );
            foreach( $statement as $stmt )
            {
                if( !is_null( $value = $this( $stmt, $args ) ) )
                     return $value;
            }

        }

        return null;
    }

    protected function isBypassed( $name, \Kinesis\Task\Statement $statement )
    {
        $method = $this->_method;

        if( $method !== 'set' &&
            $method !== 'unset' &&
            $method !== 'isset' &&
            $statement instanceof Delegate\Bypass &&
            array_key_exists( $name, $this->Source ) )
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
        if( is_null( $statement ))
            return $this->recurse( $args );

        if( get_class( $statement ) == 'Closure' )
        {
            return call_user_func_array( $statement, $args );
        }

        if( !($statement instanceof \Kinesis\Task\Statement))
            return null;

        $acc = count( $args );

        if( $acc > 0 &&
            $this->isBypassed( $args[0], $statement ))
            return $this->Source[ $args[0] ];

        if( $this->isImplemented() )
        {
            $delegate = new Delegate( $this->Reference->Container, $this->Method, $args );
            return $delegate();
        }

        $this->Arguments = $args;
        $this->_statement = $statement;

        $result = $this->execute();

        if( $acc > 0 &&
            !is_null( $result ) &&
            $statement instanceof Delegate\Bypass )
            return $this->Source[ $args[0] ] = $result;

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
            $statement->Source = $this->Reference;

            return $statement();
        }
        elseif( is_callable( $statement ) )
            return $statement( $args );

        return null;
    }
}