<?php
namespace IO;

abstract class Stream extends \Kinesis\Object
{
    private $_timeout;
    private $_encoding;
    public $Parameters;

    function __construct( array $params = array() )
    {
        parent::__construct();

        $this->setParameters( $params );
    }

    function initialise()
    {
        //parent::initialize();

        $this->Parameters = new \Util\Collection();
    }

    function getParameters()
    {
        return $this->Parameters;
    }

    function setParameters( array $params )
    {
        $this->Parameters->merge( $params );
    }

    function getDefaultEncoding()
    {
        if( $this->Parameters->exists('encoding'))
            $encoding = $this->Parameters['encoding'];
        else
            $encoding = Stream\Encoding::UTF_8;

        return new Stream\Encoding( $encoding );
    }
    function getDefaultTimeout()
    {
        if( $this->Parameters->exists('timeout'))
            return $this->Parameters['timeout'];

        return -1;
    }

    function getEncoding()
    {
        if( is_null( $this->_encoding ))
            $this->_encoding = $this->getDefaultEncoding();

        return $this->_encoding;
    }

    protected function setEncoding( $encoding )
    {
        if( is_string( $encoding ))
            $encoding = new \IO\Stream\Encoding( $encoding );

        $this->_encoding = $encoding;
    }

    function getTimeout()
    {
        if( is_null( $this->_timeout ))
            $this->_timeout = $this->getDefaultTimeout();

        return $this->_timeout;
    }

    protected function setTimeout( $timeout )
    {
        $this->_timeout = $timeout;
    }

    function __destruct()
    {
        //TODO: LOG STREAM CLOSE & IP
    }
}