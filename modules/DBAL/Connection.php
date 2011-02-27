<?php
namespace DBAL;

class Connection extends \IO\Resource\Stream
{
    public $Database;
    
    function __construct( Database $database, array $params = array() )
    {
        $this->Database = $database;

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

    function getDriver()
    {
        return $this->Database->getDriver();
    }

    function getConfiguration()
    {
        return $this->Database->getConfiguration();
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