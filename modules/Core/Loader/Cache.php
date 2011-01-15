<?php
namespace Core\Loader;

class Cache extends \Core\Cache
{
    private $_loader;

    function __construct( \Core\Loader $loader, $callback = null )
    {
        $this->_loader = $loader;
        parent::__construct( $callback );
    }

    function getLoader()
    {
        return $this->_loader;
    }

    function setLoader( \Core\Loader $loader )
    {
        $this->_loader = $loader;
    }
}
