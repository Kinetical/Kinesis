<?php
namespace Core;

class Configuration extends \Util\Collection
{
    protected $loader;

    function __construct( \Core\Loader $loader = null )
    {
        if( !is_null( $loader ))
            $this->setLoader( $loader );
        
        parent::__construct();
    }
    function getLoader()
    {
        return $this->loader;
    }

    function setLoader( \Core\Loader $loader )
    {
        $this->loader = $loader;
    }

    protected function loader( $offset )
    {
        $loader = $this->loader;
        return $loader( array('name' => $offset ) );
    }

    public function offsetGet($offset)
    {
        if( is_null( $value = parent::offsetGet( $offset )) )
            return $this->loader( $offset );

        return $value;
    }

    public function offsetSet($offset, $value)
    {
        throw new \Core\Exception('Configuration may only be read');
    }

    public function offsetUnset($offset)
    {
        throw new \Core\Exception('Configuration may only be read');
    }
}
