<?php
namespace IO\Filter;

class Map extends \Util\Collection\Iterator
{
    private $_states = array();
    
    function register( \IO\Filter $filter )
    {
        $c = count( $this->Keys );
        $this->Keys[$c] = $filter->Type->getName( false );
        $this->Data[$c] = $filter;
        $this->_states[$c] = $filter->getState();
    }

    function recurse( \IO\Filter $filter, array $params = array() )
    {
        $this->register( new \IO\Filter\Recursive( $filter, $params ));
    }

    protected function execute( array $params = array() )
    {
        return $this->current()->execute( $params );
    }

    function __invoke( $params = null )
    {
        if( !is_null( $params ) &&
            !is_array( $params ))
            $params = array( 'input' => func_get_args() );

        return $this->execute( $params );
    }

    function hasFilterState( $state )
    {
        return in_array( $state, $this->_states );
    }

    function hasFilter( $class, $state = null )
    {
        if( is_int( $state ))
        {
            $val = $this->offsetGet( $class );
            if( array_key_exists( $val, $this->_states ) &&
                $this->_states[$val] == $state )
                return true;

            return false;
        }
        return $this->offsetExists( $class );
    }

    function getFilters( $state = null )
    {
        if( is_int( $state ))
        {
            $keys = array_keys( $this->_states, $state );
            if( is_array( $keys ))
            {
                $result = array();
                foreach( $keys as $key )
                    $result[] = $this->Data[$key];

                return $result;
            }
            else
                return null;
        }
        return $this->getArray();
    }

    function setFilters( array $filters )
    {
        array_walk( $filters, array( $this, 'register' ));
    }
}