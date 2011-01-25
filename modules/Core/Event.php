<?php
namespace Core;

use \Util\Interfaces as I;

final class Event extends Delegate implements I\Nameable
{
    private $_name;
    private $_value;
    private $_processed = false;
    private $_parameters;

    public function __construct( $name = null, $params = array() )
    {
        $this->_name = $name;

        parent::__construct();

        $this->setParameters( $params );
    }

    function initialize()
    {
        parent::initialize();

        $this->_parameters = new \Util\Collection();
    }

    function setHandler( Event\Handler $handler )
    {
        $this->setCallback( $handler->getListener(), $handler->getName() );
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

    function getParameters()
    {
        return $this->_parameters;
    }

    function setParameters( array $params )
    {
        $this->_parameters->merge( $params );
    }

    function processed()
    {
        return $this->_processed;
    }

    function process()
    {
        $this->_processed = true;
    }

    function __invoke()
    {
        $this->_value = parent::__invoke();

        return $this->_value;
    }
}
