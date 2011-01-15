<?php
namespace IO;

use \Core\Interfaces as I;

class Context extends \Core\Object implements I\Parameterized
{
    private $_parameters;
    private $_options;
    private $_resource;
    
    function __construct( array $options = array(), array $params = array() )
    {
        $this->_options    = new Context\Options\Collection( $options );
        $this->_parameters = new Context\Parameter\Collection( $params );
        
        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();
        
        $this->setResource( stream_context_create( $this->getOptions(), $this->getParameters() ) );
    }

    function getResource()
    {
        return $this->_resource;
    }

    function setResource( $resource )
    {
        $this->_resource = $resource;
    }

    function getOptions()
    {
        if( empty($this->_options) )
            $this->_options->merge( stream_context_get_options( $this->_resource ) );

        return $this->_options;
    }

    function setOptions( array $options )
    {
        $this->_options->merge( $options );
    }

    function getParameters()
    {
        if( empty($this->_parameters) )
            $this->_parameters->merge( stream_context_get_params ( $this->_resource ) );

        return $this->_parameters;
    }
    function setParameters( array $params  )
    {
        $this->_parameters->merge( $params );
    }
}