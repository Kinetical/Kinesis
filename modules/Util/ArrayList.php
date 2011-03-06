<?php
namespace Util;

use Util\Interfaces as I;

class ArrayList implements I\Collection, \Countable
{
    private $_iterator = 'ArrayIterator';
    public $Data = array();
    
    function __construct( array $data = array() )
    {
        //parent::__construct();

        $this->Data += $data;
    }

    function count()
    {
        return count( $this->Data );
    }
    
    public function offsetExists($offset)
    {
        return array_key_exists( $offset, $this->Data );
    }

    public function offsetGet($offset)
    {
        return $this->Data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        return $this->Data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset( $this->Data[$offset] );
    }

    public function getIterator()
    {
        if( isset( $this->_iterator ))
        {
            $iteratorClass = $this->_iterator;
            return new $iteratorClass( $this->Data );
        }
        return new \ArrayIterator( $this->Data );
    }

    function getIteratorClass()
    {
        return $this->_iterator;
    }

    public function setIteratorClass( $name )
    {
        if( class_exists( $name ) )
            $this->_iterator = $name;
    }

    function toArray()
    {
        return $this->Data;
    }
}
