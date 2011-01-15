<?php
namespace DBAL;

use \Core\Interfaces as I;

abstract class Driver extends \Core\Object implements I\Nameable
{
    private $_name;
    private $_parameters;

    private $_platform;

    function __construct( array $params = array() )
    {
        $this->_parameters = $params;
        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();

        $this->_parameters = new \Core\Collection( $this->_parameters );
    }

    function getName()
    {
        return $this->_name;
    }

    function setName( $name )
    {
        $this->_name = $name;
    }

    function getParameters()
    {
        return $this->_parameters;
    }

    function setParameters( array $params )
    {
        $this->_parameters->merge( $params );
    }

    function getPlatform()
    {
        return $this->_platform;
    }

    function setPlatform( Platform $platform )
    {
        $this->_platform = $platform;
    }

    abstract function connect( \DBAL\Database $dataBase );
}
