<?php
namespace DBAL;

use \Util\Interfaces as I;

class Database extends \Core\Object implements I\Nameable
{
    private $_configuration;
    private $_connection;
    private $_selected = false;
    private $_context;
    private $_models;
    private $_driver;
    private $_name;
    private $_user;

    function __construct( Driver $driver, Configuration $config = null )
    {
        $this->_driver = $driver;

        if( is_null( $config ))
            $config  = new Configuration();

        $this->_configuration = $config;
        $this->_name = $config->Database['name'];

        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();

        $user = $this->getConfiguration()->getUser();
        $this->setUser( new \DBAL\Data\User( $user['name'], $user['password'] ) );
        $this->setContext( new \DBAL\Data\Context() );
        $this->setConnection( new \DBAL\Connection( $this ));
        $this->_models = new \Core\Configuration( new \DBAL\Data\Entity\Loader() );
    }

    function getName()
    {
        return $this->_name;
    }

    function setName( $name )
    {
        return $this->_name = $name;
    }

    function getUser()
    {
        return $this->_user;
    }

    function setUser( \DBAL\Data\User $user )
    {
        $this->_user = $user;
    }

    function getContext()
    {
        return $this->_context;
    }

    protected function setContext( \DBAL\Data\Context $context )
    {
        $this->_context = $context;
    }

    function getDriver()
    {
        return $this->_driver;
    }

    function getConfiguration()
    {
        return $this->_configuration;
    }

    public function getConnection()
    {
        return $this->_connection;
    }

    function setConnection( \DBAL\Connection $connection)
    {
        $this->_connection = $connection;
    }

    function connect()
    {
        return $this->_connection->open();
    }

    function disconnect()
    {
        return $this->_connection->close();
    }

    function select()
    {
        $this->_selected = $this->_driver->select( $this->_connection );
        return $this->_selected;
    }

    function selected()
    {
        return $this->_selected;
    }

    function query( $sql )
    {
        return $this->_driver->query( $sql, $this->_connection );
    }

    function getLink()
    {
        return $this->_connection->getLink();
    }

    function getPlatform()
    {
        return $this->_driver->getPlatform();
    }

    function getModels()
    {
        return $this->_models;
    }

    function setModels( array $models )
    {
        $this->_models->merge( $models );
    }
}