<?php
namespace Core;

abstract class Component extends Object implements Interfaces\Nameable
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