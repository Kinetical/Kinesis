<?php
namespace DBAL\SQL;

abstract class Transaction extends \DBAL\Query
{
    protected $database;
    
    function getDatabase()
    {
        if( is_null( $this->database ))
            $this->database = $this->getConnection()->Database;
        
        return $this->database;
    }
    
    function getPlatform()
    {
        return $this->database->getPlatform();
    }
    
    function getDriver()
    {
        return $this->database->getDriver();
    }

    function setDatabase( \DBAL\Database $database )
    {
        $this->setStream( $database->getConnection() );
        $this->database = $database;
    }
    
    function getConnection()
    {
        if( is_null( $this->Stream ))
            $this->Stream = $this->getDefaultStream();
        
        return $this->Stream;
    }

    function setConnection( \DBAL\Connection $connection )
    {
        $this->setStream( $connection );
        $this->database = $connection->Database;
    }
    
    function getDefaultIterator()
    {
        $streamCallback = $this->Parameters['StreamCallback'];
        $streamInput = $this->Parameters['StreamInput'];

        return new \DBAL\Data\Iterator( $this->getDatabase(), $streamInput, $streamCallback );
    }

    function getDefaultStream()
    {
        $core = \Core::getInstance();
        $this->setDatabase( $core->getDatabase() );
        return $this->database->getConnection();
    }
    
    
    function resolve( $connection = null )
    {
        if( $connection instanceof \DBAL\Database )
        {
            $this->setDatabase( $connection );
            $connection = $connection->getConnection();
        }
        elseif( $connection instanceof \DBAL\Connection )
        {
            $this->setDatabase( $connection->getDatabase() );
        }
        
        if( is_null( $connection ))
            $connection = $this->getConnection();

        $resolved = parent::resolve( $connection );

        if( $resolved )
        {
            $database = $this->getDatabase();

            if( !$database->selected() &&
                !$database->select() )
                 return false;

            $this->setConnection( $connection );

            return true;
        }

        return false;
    }
    
    function __invoke()
    {
        $errors = false;
        $connection = $this->getConnection();
        $platform = $this->getPlatform();
        
        $this->begin();
        
        $driver = $this->getDriver();
        
        try
        {
            $result = parent::__invoke();
            if( $driver->errors() )
            {
                throw new \DBAL\Exception('Transaction query failed to execute:');
            }
        }
        catch( \Exception $e )
        {
            $errors = true;
        }
        
        if( $errors )
            $this->rollback();
        else
            $this->commit();
        
        return $result;
        
    }
    
    protected function begin()
    {
        $platform = $this->getPlatform();
        $this->database->query( $platform->beginTransaction() );
    }

    protected function commit()
    {
        $platform = $this->getPlatform();
        $this->database->query( $platform->applyTransaction() );
    }

    protected function rollback()
    {
        $platform = $this->getPlatform();
        $this->database->query( $platform->undoTransaction() );
    }
}