<?php
namespace DBAL;

use \Util\Interfaces as I;

class Database extends \Core\Object implements I\Nameable
{
    private $_driver;
    private $_configuration;
    private $_connection;
    
    private $_context;
    private $_innerName;

    private $_user;

    function __construct( Driver $driver, Configuration $config = null )
    {
        $this->_driver = $driver;

        if( is_null( $config ))
            $config  = new Configuration();

        $this->_configuration = $config;
        $this->_innerName = $config->Database['name'];

        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();

        $user = $this->getConfiguration()->getUser();
        $this->setUser( new \DBAL\Data\User( $user['name'], $user['password'] ) );
        $this->setContext( new \DBAL\Data\Context() );
        $this->setConnection( new \DBAL\Connection( $this ));
    }

    function getName()
    {
        return $this->_innerName;
    }

    function setName( $name )
    {
        return $this->_innerName = $name;
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
        return $this->getPlatform()->select( $this->_connection );
    }

    function query( $sql )
    {
        return $this->getPlatform()->query( $sql, $this->_connection );
    }

    function getLink()
    {
        return $this->_connection->getLink();
    }

    function getPlatform()
    {
        return $this->_driver->getPlatform();
    }

    /*function getLoader()
    {
        return $this->_loaders['entity'];
    }

    function setLoader( \ORM\Entity\EntityLoader $loader )
    {
        $this->_loaders['entity'] = $loader;
    }

    function hasEntity( $entityName, $autoload = true )
    {
        if( $autoload &&
           ($exists = array_key_exists( $entityName, $this->_entities )) == false )
                return $this->addEntity( $this->Loader->loadPath( $entityName ) );
        return $exists;
    }
    function addEntity( \ORM\Entity\SQLEntity $entity )
    {
        return $this->_entities[ $entity->OuterName ] = $entity;
    }
    function removeEntity( $name )
    {
        unset( $this->_entities[ $name ] );
    }

    function setEntities( array $entities )
    {
        foreach( $entities as $entity )
                $this->addEntity( $entity );
    }
    function getEntities()
    {
        return $this->_entities;
    }
   */
}