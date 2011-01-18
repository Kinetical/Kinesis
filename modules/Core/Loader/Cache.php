<?php
namespace Core\Loader;

abstract class Cache extends \Core\Cache
{
    private $_loader;

    function __construct( \Core\Loader $loader )
    {
        $this->_loader = $loader;
        parent::__construct();
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
