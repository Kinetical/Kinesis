<?php
namespace DBAL\Query;

final class Transaction extends \Core\Object
{
    protected $parameters;
	protected $commands;
        protected $connection;

	private $_prepared = false;
        private $_platform;
        private $_driver;

        function __construct( array $params = array(), \DBAL\Connection $connection = null )
        {
            if( !is_null( $connection ))
                $this->setConnection ($connection);

            parent::__construct();

            $this->setParameters( $params );
        }

        function initialize()
        {
            parent::initialize();

            $this->commands = new \Util\Collection\Dictionary( array(), 'DBAL\Query' );
            $this->parameters = new \Util\Collection();
        }

        function getParameters()
        {
            return $this->parameters;
        }

        function setParameters( array $params )
        {
            $this->parameters->merge( $params );
        }

        function getCommands()
        {
            return $this->commands;
        }

        function setCommands( array $commands )
        {
            $this->commands->merge( $commands );
        }

        function getConnection()
        {
            return $this->connection;
        }

        function setConnection( \DBAL\Connection $connection )
        {
            $this->_platform = $connection->getPlatform();
            $this->_driver = $connection->getDriver();
            
            $this->connection = $connection;
        }

        function getPlatform()
        {
            return $this->_platform;
        }

        function getDriver()
        {
            return $this->_driver;
        }

        function query()
        {
            $queryClass = $this->parameters['QueryClass'];

            if( class_exists( $queryClass ))
                return new $queryClass;

            throw new \DBAL\Exception('Transaction cannot create query');
        }

        function build()
        {
            return $this->query()->build();
        }

        function begin()
        {
            $this( $this->build()->begin() );
            //TODO: BEGIN QUERY NODE
        }

	protected function execute( $command = null )
	{
            if( !is_null( $command ) &&
                 $command instanceof \DBAL\Query &&
                 $command instanceof \DBAL\Query\Builder )

            $results = array();

            foreach( $this->commands as $command )
                $results[ $command->Oid ] = $command( $this->connection );

            return $results;
	}

        function commit()
        {
            $this( $this->build()->commit() );
        }

        function rollback()
        {
            $this( $this->build()->rollback() );
        }

        function __invoke( $connection = null )
        {
            $args == func_get_args();
            foreach( $args as $arg )
                if( $arg instanceof \DBAL\Connection )
                    $this->setConnection( $arg );
                elseif( $arg instanceof \DBAL\Query ||
                        $arg instanceof \DBAL\Query\Builder )
                        $command = $arg;

            return $this->execute();
        }
}