<?php
namespace Core\Component;

use \Core\Interfaces as I;

class Parameter extends \Core\Object implements I\Nameable
{
    private $_name;
    private $_value;

    function __construct( $name, $value )
    {
        $this->setName( $name );
        $this->setValue( $value );
        parent::__construct();
    }

    function getName()
    {
        return $this->_name;
    }

    function setName( $name )
    {
        $this->_name = $name;
    }

    function getValue()
    {
        return $this->_value;
    }

    function setValue( $value )
    {
        $this->_value = $value;
    }

    function __toString()
    {
        return (string)$this->_value;
    }
}