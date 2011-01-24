<?php
namespace Core;

abstract class Filter extends Object
{
    protected $parameters;
    protected $delegate;

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
    
    function getDelegate()
    {
        return $this->delegate;
    }
    
    function setDelegate( $delegate )
    {
        if( $delegate instanceof Filter )
            $delegate = new Delegate( $filter );

        if( !($delegate instanceof Delegate)
            || !$delegate->isType('Core\Filter') )
            throw new \Core\Exception('Filter delegate must be instance of a Filter');

        $this->delegate = $delegate;
    }
    
    function hasDelegate()
    {
        return ( $this->delegate instanceof Delegate );
    }

    function __invoke( $params = null )
    {
        if( !is_null( $params )
            && !is_array( $params ))
            $params = func_get_args();

        if( $this->hasDelegate() )
        {
            $delegate = $this->delegate;
            return $delegate( $params );
        }

        return $this->execute( $params );
    }

    abstract protected function execute( array $params = null );
}