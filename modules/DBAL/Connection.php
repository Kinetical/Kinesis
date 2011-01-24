<?php
namespace DBAL;

abstract class Connection extends Stream
{
    private $_driver;
    private $_configuration;
    
    
    function __construct( Driver $driver, Configuration $config = null, array $params = array() )
    {
        $this->_driver = $driver;

        if( !is_null( $config ))
            $this->_configuration = $config;
        else
            $this->_configuration = new Configuration();

        parent::__construct( $params );
    }

    function getLink()
    {
        return $this->_stream->getResource();
    }

    function setLink( $link )
    {
        $this->_stream->setResource( $link );
    }

    public function getDatabase()
    {
        return $this->_driver->getDatabase($this);
    }

    function getPlatform()
    {
        return $this->_driver->getPlatform();
    }

    function getDriver()
    {
        return $this->_driver;
    }

    function getConfiguration()
    {
        return $this->_configuration;
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
        $link = $this->_driver->connect( $this );

        $this->setLink( $link );

        return $link;
    }

    function close()
    {
        return $this->_driver->close( $this );
    }

    function isConnected()
    {
        return isset( $this->isOpen() );
    }
}