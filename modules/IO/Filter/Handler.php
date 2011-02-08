<?php
namespace IO\Filter;

class Handler extends \IO\Filter
{
    protected $map;

    function __construct()
    {
        $this->map = new \IO\Filter\Map();

        $args = func_get_args();
        foreach( $args as $arg )
            if( is_array( $arg ))
                $params = $arg;
            elseif( $arg instanceof \IO\Filter )
                $this->map->register( $arg );

        if( !is_array( $params ))
            $params = array();

        parent::__construct( $params );
    }

    function getMap()
    {
        return $this->map;
    }

    function setMap( $map )
    {
        if( $map instanceof \IO\Filter\Map )
            $map = $map->getFilters();
        
        $this->map->setFilters( $map );
    }

    function hasMap( $state = null )
    {
        if( is_int( $state ))
            return $this->map->hasFilterState( $state );
        
        return ($this->map->count() > 0 )
                ? true
                : false;
    }

    protected function execute( array $params = array() )
    {
        $input = $params['input'];
        $state = $params['state'];
        
        if( !$this->hasFilters( $state ) )
            return $input;

        $filters = $this->map->getFilters( $state );

        foreach( $filters as $filter )
        {
            if( !is_null( $output ))
                $params['input'] = $output;

            if( is_array( $output ))
                foreach( $output as $key => $value )
                    $output[$key] = $filter( array('input' => $value, 'state' => $state ) );
            else
                $output = $filter( $params );
        }

        return $output;
    }
}
