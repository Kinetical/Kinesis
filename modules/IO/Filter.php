<?php
namespace IO;

abstract class Filter extends \Core\Object
{
    const INPUT = 1;
    const OUTPUT = 2;
    
    protected $parameters;
    protected $delegate;
    protected $state = 2;

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
            || !$delegate->isType('IO\Filter') )
            throw new \Core\Exception('Filter delegate must be instance of a Filter');

        $this->delegate = $delegate;
    }
    
    function hasDelegate()
    {
        return ( $this->delegate instanceof Delegate );
    }

    function getState()
    {
        return $this->state;
    }

    function __invoke( $params = null )
    {
        if( !is_null( $params ) &&
            !is_array( $params ))
            $params = array( 'input' => func_get_args() );

        if( $this->hasDelegate() )
        {
            $delegate = $this->delegate;
            return $delegate( $params );
        }

        return $this->execute( $params );
    }

    abstract protected function execute( array $params = null );
}