<?php
namespace Core;

class Iterator extends \Core\Object implements \Iterator, \Countable
{
    protected $keys;
    private $_position = 0;

    function __construct( $array = null )
    {
        if( $array instanceof \Traversable
            || is_array( $array ))
            $this->setArray( $array );
    }

    function next()
    {
        $this->increment();
    }

    function valid()
    {
        if( array_key_exists( $this->_position, $this->Data ))
            return true;

        return false;
    }

    function rewind()
    {
        $this->_position = 0;
    }

    function current()
    {
        return $this->Data[ $this->_position ];
    }

    function key()
    {
        return $this->keys[ $this->_position ];
    }

    function clear()
    {
        $this->Data = array();
        $this->keys = array();
    }

    function setArray( array $array )
    {
        $this->Data = array_values( $array );
        $this->keys = array_keys( $array );
    }
    
    function getArray()
    {
        return array_combine( $this->keys, $this->Data );
    }

    protected function increment()
    {
        ++$this->_position;
    }

    protected function decrement()
    {
        --$this->_position;
    }

    protected function position()
    {
        return $this->_position;
    }

    function count()
    {
        return count( $this->Data );
    }
}