<?php
namespace Util;

abstract class Iterator extends \Kinesis\Object implements \Iterator
{
    protected $position = 0;
    public $Data = array();
    
    function valid()
    {
        return false;
    }
    
    function current()
    {
        return null;
    }

    function key()
    {
        return $this->position;
    }

    function next()
    {
        $this->increment();
    }

    function rewind()
    {
        $this->position = 0;
    }

    function clear()
    {
        $this->rewind();
    }

    protected function increment()
    {
        return $this->shift( 1 );
    }

    protected function decrement()
    {
        return $this->shift( -1 );
    }

    protected function position( $position = null )
    {
        if( is_int( $position ))
        {
            $this->position = $position;
            return $this;
        }

        return $this->position;
    }

    protected function shift( $amount )
    {
        return $this->position( $this->position + $amount );
    }
}