<?php
namespace Kinesis;

class Container
{
    protected $reference;

    function reference()
    {
        if( is_null( $this->reference ) )
            $this->reference = new Reference\Base( $this );
        
        return $this->reference;
    }
    
    private function integrity()
    {        
        if( is_null( $this->reference ))
        {
            $this->reference = $this->reference();
            Instantiator::initialise( $this->reference );
        }
    }

    function __construct()
    {
        $this->integrity();
    }
    
    function __destruct()
    {
        unset( $this->reference );
    }
    
    function __sleep()
    {
        $vars = get_object_vars( $this );
        
        unset( $vars['reference']);
        
        return array_keys($vars);
    }
}