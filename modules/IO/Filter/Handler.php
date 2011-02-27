<?php
namespace IO\Filter;

class Handler extends \IO\Filter
{
    public $Map;
    protected $map;

    function __construct()
    {
        $this->Map = new \IO\Filter\Map();

        $args = func_get_args();
        foreach( $args as $arg )
            if( is_array( $arg ))
                $params = $arg;
            elseif( $arg instanceof \IO\Filter )
                $this->Map->register( $arg );

        if( !is_array( $params ))
            $params = array();

        parent::__construct( $params );
    }

//    function getMap()
//    {
//        return $this->Map;
//    }
//
//    function setMap( $map )
//    {
//        if( $map instanceof \IO\Filter\Map )
//            $map = $map->getFilters();
//        
//        if( is_null( $map ))
//            $this->map->clear();
//        else
//            $this->map->setFilters( $map );
//    }
    
    function setFilters( $filters )
    {
        $this->Map->setFilters( $filters );
    }

    function hasMap( $state = null )
    {
        if( is_int( $state ))
            return $this->Map->hasFilterState( $state );
        
        return ($this->Map->count() > 0 )
                ? true
                : false;
    }

    protected function execute( array $params = array() )
    {
        $input = $params['input'];
        $state = $params['state'];
        
        if( !$this->hasMap( $state ) )
            return $input;

        $filters = $this->Map->getFilters( $state );

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
