<?php
namespace DBAL\SQL;

class Query extends \DBAL\Query
{
    private $_text;
    private $_database;

    function __construct( $text = null, $params = array() )
    {
        if( !is_null( $text ))
            $this->setText( $text );

        parent::__construct( $params );
    }

    function initialize()
    {
        parent::initialize();
        $this->setFormat( self::SQL );
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
        return $this->getStream();
    }

    function setConnection( \DBAL\Connection $connection )
    {
        $this->setStream( $connection );
    }

    function getDefaultIterator()
    {
        $streamCallback = $this->parameters['StreamCallback'];
        $streamInput = $this->parameters['StreamInput'];

        return new \DBAL\Data\Iterator( $this->getDatabase(), $streamInput, $streamCallback );
    }

    function getDefaultStream()
    {
        $core = \Core::getInstance();
        $this->setDatabase( $core->getDatabase() );
        return $this->_database->getConnection();
    }

    function execute( $connection = null )
    {
        $this->parameters['StreamInput'] = $this->_database->query( $this->getText() );

        $this->results->clear();

        foreach( $this as $result )
            $this->results->add( $this->filter( $result ) );

        return $this->results->toArray();
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


    function __toString()
    {
        if( is_null( $this->_text ) &&
            $this->builder instanceof \DBAL\Query\Builder )
            $this->_text = (string)$this->builder;

        return $this->_text;
    }
}
