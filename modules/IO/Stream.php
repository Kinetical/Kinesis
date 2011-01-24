<?php
namespace IO;

abstract class Stream extends \Core\Object
{
    private $_timeout;
    private $_encoding;
    protected $parameters;

    function __construct( array $params = array() )
    {
        parent::__construct();

        $this->setParameters( $params );
    }

    function initialize()
    {
        parent::initialize();

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

    function getDefaultEncoding()
    {
        if( $this->parameters->exists('encoding'))
            $encoding = $this->parameters['encoding'];
        else
            $encoding = Stream\Encoding::UTF_8;

        return new Stream\Encoding( $encoding );
    }
    function getDefaultTimeout()
    {
        if( $this->parameters->exists('timeout'))
            return $this->parameters['timeout'];

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
}