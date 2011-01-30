<?php
namespace DBAL;

class Connection extends \IO\Resource\Stream
{
    private $_database;
    
    function __construct( Database $database, array $params = array() )
    {
        $this->_database = $database;

        parent::__construct( $params );
    }

    function getLink()
    {
        return $this->getPointer();
    }

    function setLink( $link )
    {
        $this->setPointer( $link );
    }

    public function getDatabase()
    {
        return $this->_database;
    }

    function setDatabase( Database $database )
    {
        $this->_database = $database;
    }

    function getDriver()
    {
        return $this->_database->getDriver();
    }

    function getConfiguration()
    {
        return $this->_database->getConfiguration();
    }

    public function getDefaultTimeout()
    {
        return 30;
    }

    function getDefaultEncoding()
    {
        return 'UTF-8';
    }

    function open()
    {
        $link = $this->getDriver()->connect( $this );

        $this->setLink( $link );

        return $link;
    }

    function close()
    {
        return $this->getDriver()->disconnect( $this );
    }

    function isConnected()
    {
        return $this->isOpen();
    }
}