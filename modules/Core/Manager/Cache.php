<?php
namespace Core\Manager;

class Cache extends \Core\Cache
{
    private $_manager;

    function __construct( \Core\Manager $manager, $callback = null )
    {
        $this->_manager = $manager;
        parent::__construct( $callback );
    }

    function getLoader()
    {
        return $this->_manager;
    }

    function setLoader( \Core\Manager $manager )
    {
        $this->_manager = $manager;
    }
}