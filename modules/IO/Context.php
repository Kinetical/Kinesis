<?php
namespace IO;

use \Util\Interfaces as I;

class Context extends \Kinesis\Object implements I\Parameterized
{
    public $Parameters;
    protected $options;
    private $_resource;
    
    function __construct( array $options = array(), array $params = array() )
    {
        parent::__construct();

        $this->setOptions( $options );
        $this->setParameters( $params );
    }

    function initialise()
    {
        //parent::initialize();

        $this->options    = new Context\Options\Collection();
        $this->Parameters = new Context\Parameter\Collection();
        
        $this->setResource( stream_context_create( $this->options->toArray(),
                                          $this->Parameters->toArray() ) );
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
        if( empty($this->Parameters) )
            $this->Parameters->merge( stream_context_get_params ( $this->_resource ) );

        return $this->Parameters;
    }
    function setParameters( array $params  )
    {
        $this->Parameters->merge( $params );
    }
}