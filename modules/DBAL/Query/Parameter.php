<?php
namespace DBAL\Query;

use \Core\Interfaces as I;

abstract class Parameter extends \Core\Object implements I\Nameable
{
    private $_name;

    function __construct( $name )
    {
        $this->setName( $name );
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
}