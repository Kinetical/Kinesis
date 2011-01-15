<?php
namespace Core;

abstract class Filter extends Object
{
    private $_parameters;

    function __construct( array $params = null )
    {
        parent::__construct();
        if( is_array( $params ))
            $this->setParameters( $params );
    }

    function initialize()
    {
        parent::initialize();
        
        $this->_parameters = new \Core\Collection();

        if( !$this->Type->hasEvent( 'execute' ))
             $this->Type->addEvent( new \Core\Event('execute') );
    }

    function getParameters()
    {
        return $this->_parameters;
    }

    function setParameters( array $params )
    {
        $this->_parameters->merge( $params );
    }

    abstract protected function execute( $input, $params = null );
}