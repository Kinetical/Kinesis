<?php
namespace Core;

abstract class Manager extends Object
{
    protected $loaders;
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
        $this->loaders = new \Util\Collection\Dictionary( array(), 'Core\Loader' );
    }

    function getParameters()
    {
        return $this->parameters;
    }

    function setParameters( array $params )
    {
        $this->parameters->merge( $params );
    }

    function getLoaders()
    {
        return $this->loaders;
    }

    function setLoaders( array $loaders )
    {
        $this->loaders->merge( $loaders );
    }

    protected function execute( array $params = null )
    {
        foreach( $this->loaders as $loader )
        {
            $value = $loader( $params );
            if( !is_null( $value ))
                return $value;
        }

        return null;
    }

    function __invoke( array $params = null )
    {
        return $this->execute( $params );
    }
        
}