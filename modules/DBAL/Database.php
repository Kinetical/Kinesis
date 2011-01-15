<?
namespace DBAL;

use Core\Interfaces as Interfaces;

class Database extends \Core\Object implements Interfaces\Nameable
{
    private $_context;
    private $_connection;
    private $_innerName;

    private $_user;

    function __construct( $innerName, Connection $connection )
    {
        $this->_innerName = $innerName;
        $this->_connection = $connection;

        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();

        $user = $this->getConfiguration()->getUser();
        $this->setUser( new \DBAL\Data\User( $user['name'], $user['password'] ) );
        $this->setContext( new \DBAL\Data\Context() );
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

    public function getConnection()
    {
        return $this->_connection;
    }

    function setConnection( \DBAL\Connection $connection)
    {
        $this->_connection = $connection;
    }

    function getConfiguration()
    {
        return $this->_connection->getConfiguration();
    }

    function getDriver()
    {
        $this->_connection->getDriver();
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