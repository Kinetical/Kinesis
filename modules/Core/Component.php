<?php
namespace Core;

use \Util\Interfaces as I;

abstract class Component implements I\Nameable
{
    private $_name;

    function getName()
    {
        return $this->_name;
    }
    function setName( $name )
    {
        $this->_name = $name;
    }
}