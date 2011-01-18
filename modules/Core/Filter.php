<?php
namespace Core;

abstract class Filter extends Object
{
    protected $parameters;

    function __construct( array $params = null )
    {
        parent::__construct();
        if( is_array( $params ))
            $this->setParameters( $params );
    }

    function initialize()
    {
        parent::initialize();
        
        $this->parameters = new \Core\Collection();
    }

    function getParameters()
    {
        return $this->parameters;
    }

    function setParameters( array $params )
    {
        $this->parameters->merge( $params );
    }

    function __invoke( array $params = null )
    {
        return $this->execute( $params );
    }

    abstract function execute( array $params = null );
}