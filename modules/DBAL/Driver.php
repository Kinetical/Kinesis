<?php
namespace DBAL;

use \Util\Interfaces as I;

abstract class Driver extends \Kinesis\Object implements I\Nameable
{
    protected $parameters;

    protected $platform;

    function __construct( array $params = null )
    {
        parent::__construct();

        if( is_array( $params ))
        $this->setParameters( $params );
    }

    function initialise()
    {
        //parent::initialize();

        $this->parameters = new \Util\Collection();
    }

    function getName()
    {
        return $this->parameters['name'];
    }

    function setName( $name )
    {
        $this->parameters['name'] = $name;
    }

    function getParameters()
    {
        return $this->parameters;
    }

    function setParameters( array $params )
    {
        $this->parameters->merge( $params );
    }

    function getPlatform()
    {
        return $this->platform;
    }

    function setPlatform( Platform $platform )
    {
        $this->platform = $platform;
    }

    abstract function connect( \DBAL\Connection $dataBase );
}
