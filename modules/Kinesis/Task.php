<?php
namespace Kinesis;

abstract class Task extends Object
{
    public $Parameters = array();

    function __construct( array $params = null )
    {
        if( !is_null( $params ))
            $this->Parameters = $params;

        parent::__construct();
    }
    
    abstract protected function execute();

    function __invoke()
    {
        if( func_num_args() > 0 )
            $args = func_get_args();
        else
            $args = array();

        return $this->execute( $args );
    }

}