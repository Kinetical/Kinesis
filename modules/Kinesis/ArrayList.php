<?php
namespace Kinesis;

class ArrayList extends Container implements \ArrayAccess, \Countable
{
    public $Data;
    
    function __construct( array $data = array() )
    {
        $this->Data = $data;
        
        parent::__construct();
    }
    
    protected function reference()
    {
        if( is_null( $this->reference ))
            $this->reference = new Reference\ArrayList( $this );
        return $this->reference;
    }
    
    public function offsetGet($offset) 
    {
        return $this->reference->offsetGet( $offset );
    }
    
    public function offsetUnset($offset) 
    {
        $this->reference->offsetUnset( $offset );
    }
    
    public function offsetSet($offset, $value)
    {
        $this->reference->offsetSet( $offset, $value );
    }
    
    public function offsetExists($offset)
    {
        return $this->reference->offsetExists( $offset );
    }
    
    function count()
    {
        return count( $this->Data );
    }
}