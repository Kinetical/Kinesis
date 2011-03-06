<?php
namespace Core;

abstract class Cache extends \Util\Collection
{
    private $_enabled = true; //TODO: RETRIEVE FROM CONFIG FILE

    public $Parameters;

    function __construct( array $params = array() )
    {
        parent::__construct();

        $this->setParameters( $params );
    }

    function initialise()
    {
        //parent::initialize();

        $this->Parameters = new \Util\Collection();
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

    function getParameters()
    {
        return $this->Parameters;
    }

    function setParameters( array $params )
    {
        $this->Parameters->merge( $params );
    }

    abstract protected function has( $id );
    abstract protected function load( $id );
    abstract protected function save( $id, $value );
    abstract protected function dirty( $id );
    abstract protected function delete( $id );

    function offsetExists( $offset )
    {
        if( parent::offsetExists( $offset ) === true)
        {
            return true;
        }

        if( $this->Parameters->exists('hash') )
            $offset = $this->hash( $offset );
        
        return $this->has( $offset );
    }

    function offsetSet( $offset, $value )
    {
        parent::offsetSet( $offset, $value );

        if( $this->Parameters->exists('hash') )
            $offset = $this->hash( $offset );

        if( !is_object( $value ))
            throw new \Core\Exception( 'Cannot only cache objects, '. gettype( $value) .' provided');

        $this->save( $offset, clone $value );
    }

    function offsetGet( $offset )
    {
        if( array_key_exists( $offset, $this->Data ))
            return $this->Data[$offset];

        if( $this->Parameters->exists('hash') )
            $offset = $this->hash( $offset );

        return $this->load( $offset );
    }

    function offsetUnset( $offset )
    {
        parent::offsetUnset( $offset );

        if( $this->Parameters->exists('hash') )
            $offset = $this->hash( $offset );

        $this->remove( $offset );
    }

    protected function hash( $offset )
    {
        $hash = $this->Parameters['hash'];

        if( is_callable( $hash ))
            return $hash( $offset );

        return md5( $offset );
    }
}
