<?php
namespace Core\Event;

use \Util\Interfaces as I;

class Handler extends \Core\Object implements I\Nameable
{
    private $_listener;
    private $_name;

    function __construct( $name, Listener $listener )
    {
        $this->_listener = $listener;
        $this->_name = $name;

        parent::__construct();
    }

    function getListener()
    {
        return $this->_listener;
    }

    function setListener( Listener $listener )
    {
        $this->_listener = $listener;
    }

    function getName()
    {
        return $this->_name;
    }

    function setName( $name )
    {
        $this->_name = $name;
    }
}