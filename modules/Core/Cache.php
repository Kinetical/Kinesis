<?php
namespace Core;

abstract class Cache extends Collection
{
    private $_enabled = true; //TODO: MOVE TO CONFIG FILE

    function __construct()
    {
        parent::__construct();
    }

    function enable()
    {
        $this->_enabled = true;
    }

    function disable()
    {
        $this->_enabled = false;
    }

    function enabled()
    {
        return $this->_enabled;
    }

    abstract protected function has( $id );
    abstract protected function load( $id );
    abstract protected function save( $id, $value );
    abstract protected function delete( $id );
    abstract protected function changed( $id );

    function offsetExists( $offset )
    {
        if( parent::offsetExists( $offset ))
            return true;
        
        return $this->has( $offset );
    }

    function offsetSet( $offset, $value )
    {
        parent::offsetSet( $offset, $value );

        $this->save( $id, $value );
    }

    function offsetGet( $offset )
    {
        if( array_key_exists( $offset, $this->Data ))
            return $this->Data[$offset];

        return $this->load( $offset );
    }

    function offsetUnset( $offset )
    {
        parent::offsetUnset( $offset );

        $this->remove( $offset );
    }
}
