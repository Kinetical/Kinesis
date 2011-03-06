<?php
namespace Kinesis;

abstract class Container
{
    protected $reference;

    abstract protected function reference();
    
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
}