<?php
namespace DBAL\Data\Filter;

class Handler extends \Core\Filter
{
    protected $filters;

    function __construct()
    {
        $this->filters = new \Core\Filter\Chain();

        $args = func_get_args();
        foreach( $args as $arg )
            if( is_array( $arg ))
                $params = $arg;
            elseif( $arg instanceof \Core\Filter )
                $this->filters->register( $arg );

        if( !is_array( $params ))
            $params = array();

        parent::__construct( $params );
    }

    function getFilters()
    {
        return $this->filters;
    }

    function setFilters( \Core\Filter\Chain $filters )
    {
        $this->filters = $filters;
    }

    function hasFilters()
    {
        return ($this->filters->count() > 0 )
                ? true
                : false;
    }

    protected function execute( array $params = array() )
    {
        $input = $params['input'];
        
        if( !$this->hasFilters() )
            return $input;

        foreach( $this->filters as $filter )
        {
            if( !is_null( $output ))
                $params['input'] = $output;

            if( is_array( $output ))
                foreach( $output as $key => $value )
                    $output[$key] = $filter( array('input' => $value ) );
            else
                $output = $filter( $params );
        }

        return $output;
    }
}
