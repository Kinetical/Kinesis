<?php
namespace Core;

use \Core\Interfaces as I;

class Collection extends Object implements I\Collection, \Countable
{
    private $_iterator = 'ArrayIterator';
    private $_type = null;

    function __construct()
    {
        parent::__construct();

        if( is_array( $args = func_get_args() ))
            foreach( $args as $value )
                if( is_string( $value ))
                    $this->setDataType( $value );
                elseif( is_array( $value ))
                    $this->Data = $value;
    }

    function add( $value, $key = null )
    {
        if( is_null( $key ))
            $key = $this->count();

        $this->insert( $key, $value );
    }

    function insert( $key, $value )
    {
        if( $key == null
            && $value instanceof I\Nameable )
            $key = $value->getName();
        elseif( $key == null )
            $key = $this->count();

        $this->offsetSet( $key, $value );
    }

    function remove( $key )
    {
        $this->offsetUnset( $key );
    }

    function exists( $offset )
    {
            return $this->offsetExists( $offset );
    }

    function merge( array $data )
    {
        $this->Data += $data;
    }

    function clear()
    {
        $this->Data = array();
    }

    public function offsetExists($offset)
    {
            return $this->__isset( $offset );
    }

    public function offsetGet($offset)
    {
            return $this->__get( $offset );
    }

    public function offsetSet($offset, $value)
    {
            return $this->__set( $offset, $value );
    }

    public function offsetUnset($offset)
    {
            return $this->__unset( $offset );
    }

    public function __set( $name, $value )
    {
        $methodName = 'set'.$name;
        if( $this->isStronglyTyped()
                && !method_exists( $this, $methodName )
                && !($value instanceof $this->_type ))
                throw new Exception('DataArray does not accept items of type: '. get_class( $value ));

        parent::__set( $name, $value );
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

    function setDataType( $type )
    {
        if( class_exists( $type )
            || interface_exists( $type ))
            $this->_type = $type;
        else
            throw new \Core\Exception( 'Invalid data type('.$type.')' );
    }

    function getDataType()
    {
        if( $this->isStronglyTyped() )
                return $this->_type;

        return false;
    }

    function count()
    {
        return count( $this->Data );
    }

    function isStronglyTyped()
    {
        return $this->_type !== null 
                ? true
                : false;
    }

    function isWeaklyTyped()
    {
        return !$this->isStronglyTyped();
    }

    function toArray()
    {
        return $this->Data;
    }
}