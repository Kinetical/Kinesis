<?php
namespace Kinesis;

abstract class Component extends Object
{
    public $Parameters = array();

    function __construct( array $params = null )
    {
        if( !is_null( $params ))
            $this->Parameters = $params;

        parent::__construct();
    }
    
    function setParameters( array $array )
    {
        if( $this->Parameters instanceof \Util\Collection )
            $this->Parameters->merge( $array );
        
        $this->Parameters += $array;
    }
    
    function getParameters()
    {
        return $this->Parameters;
    }
}