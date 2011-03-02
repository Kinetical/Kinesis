<?php
namespace DBAL;

use \Util\Interfaces as I;

abstract class Driver extends \Kinesis\Object implements I\Nameable
{
    public $Parameters;

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

        $this->Parameters = new \Util\Collection();
    }

    function getName()
    {
        return $this->Parameters['name'];
    }

    function setName( $name )
    {
        $this->Parameters['name'] = $name;
    }

    function getParameters()
    {
        return $this->Parameters;
    }

    function setParameters( array $params )
    {
        $this->Parameters->merge( $params );
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
