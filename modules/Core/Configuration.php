<?php
namespace Core;

class Configuration extends Collection
{
    private $_loader;

    function initialize()
    {
        parent::initialize();

        $this->setLoader(new Configuration\Loader());
    }

    function getLoader()
    {
        return $this->_loader;
    }

    function setLoader( \Core\Loader $loader )
    {
        $this->_loader = $loader;
    }

    public function offsetExists($offset) {
        if( ( $bool = parent::offsetExists( $offset )) == false )
            return $this->_loader->match( $offset );

        return $bool;
    }

    public function offsetGet($offset) {
        if( ( $value = parent::offsetGet( $offset )) == null )
            return $this->_loader->load( $offset );

        return $value;
    }

    public function offsetSet($offset, $value) {
            return $this->__set( $offset, $value );
    }

    public function offsetUnset($offset) {
            return $this->__unset( $offset );
    }
}
