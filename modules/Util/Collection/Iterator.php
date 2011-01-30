<?php
namespace Util\Collection;

class Iterator extends \Util\Iterator implements \ArrayAccess, \Countable
{
    public $Keys;

    function __construct( $array = null )
    {
        if( $array instanceof \Traversable
            || is_array( $array ))
            $this->setArray( $array );
    }

    function valid()
    {
        if( array_key_exists( $this->position, $this->Data ))
            return true;

        return false;
    }

    function current()
    {
        return $this->Data[ $this->position ];
    }

    function key()
    {
        return $this->Keys[ $this->position ];
    }

    function clear()
    {
        $this->Data = array();
        $this->Keys = array();

        parent::clear();
    }

    function setArray( array $array )
    {
        $this->Data = array_values( $array );
        $this->Keys = array_keys( $array );
    }

    function getArray()
    {
        return array_combine( $this->Keys, $this->Data );
    }

    function toArray()
    {
        return $this->getArray();
    }

    function count()
    {
        return count( $this->Data );
    }

    public function offsetExists($offset)
    {
        return in_array( $offset, $this->Keys );
    }

    public function offsetGet($offset)
    {
        $key = array_search( $offset, $this->Keys );
        return $this->Data[$key];
    }

    public function offsetSet($offset, $value)
    {
        $this->Keys[] = $offset;
        return $this->Data[] = $value;
    }

    public function offsetUnset($offset)
    {
        $key = array_search( $offset, $this->Keys );

        unset( $this->Data[$key] );
        unset( $this->Keys[$key] );
    }
}