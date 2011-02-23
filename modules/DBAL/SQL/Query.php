<?php
namespace DBAL\SQL;

class Query extends \DBAL\Query
{
    private $_text;
    private $_database;
    
    function initialise()
    {
        $this->Parameters['Namespace'] = 'DBAL\SQL\Query';
    }

    function __construct( $text = null, $params = array() )
    {
        if( !is_null( $text ))
            $this->setText( $text );

        parent::__construct( $params );
    }

    function getDatabase()
    {
        if( is_null( $this->_database ))
            $this->_database = $this->getConnection()->getDatabase();
        
        return $this->_database;
    }

    function setDatabase( \DBAL\Database $database )
    {
        $this->setStream( $database->getConnection() );
        $this->_database = $database;
    }

    function getConnection()
    {
        return $this->Stream;
    }

    function setConnection( \DBAL\Connection $connection )
    {
        $this->setStream( $connection );
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
        return $this->_database->getConnection();
    }

    function execute()
    {
        $this->Parameters['StreamInput'] = $this->_database->query( $this->getText() );

        $results = array();

        foreach( $this as $result )
            $results[] = $result ;

        return $results;
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

    function getText()
    {
        if( is_null( $this->_text ))
            $this->_text = (string)$this;
        
        return $this->_text;
    }

    function setText( $text )
    {
        if( !is_string( $text ))
            throw new \InvalidArgumentException('Query text must be string, '.get_class( $text ).' provided.');

        $this->_text = $text;
    }

    function assemble()
    {
        $this->_text = parent::assemble();
        
        return $this->_text;
    }

    function __toString()
    {
        if( is_null( $this->_text ) )
            $this->assemble();

        return $this->_text;
    }
}
