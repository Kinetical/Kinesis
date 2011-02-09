<?php
namespace IO\Stream;

use IO\Filter;

class Iterator extends \Util\Iterator
{
    private $_outputBuffer;
    private $_inputBuffer;
    private $_stream;
    private $_shared = false;
    private $_handler;

    protected $delegate;

    function __construct( \Core\Delegate $delegate = null, \IO\Stream $stream = null )
    {
        $this->delegate = $delegate;
        $this->_stream = $stream;

        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();

        $this->_inputBuffer = new \Util\Collection();
    }

    function hasDelegate()
    {
        return !is_null( $this->delegate ) ;
    }

    protected function getInputArguments()
    {
        if( $this->isShared() )
            $position = 0;
        else
            $position = $this->position;


        $input = $this->_inputBuffer[ $position ];

        if( $this->inputFiltered() )
        {
            $handler = $this->_handler;

            $params = array('input' => $input,
                            'state' => Filter::INPUT );

            $input = $handler( $params );
        }

        return $input;
    }

    private function inputFiltered()
    {
        if( $this->hasMap() )
            if( $this->isShared() &&
                $this->position < 1 )
                return true;
            else
                return true;

        return false;
    }

    public function current()
    {
        $delegate = $this->delegate;

        $buffer = $delegate( $this->getInputArguments() );

        if( $this->hasMap() )
        {
            $handler = $this->_handler;

            $params = array('input' => $buffer,
                            'state' => Filter::OUTPUT );

            $buffer = $handler( $params );
        }

        $this->_outputBuffer = $buffer;

        return $buffer;
    }

    function rewind()
    {
        unset($this->_outputBuffer);
        $this->position = 0;

        $stream = $this->getStream();
        if( $stream->Type->hasMethod('rewind'))
            $stream->rewind();
    }

    function valid()
    {
        $stream = $this->getStream();

        if( $stream->eof() )
            return false;

        try {
            if( !$stream->isOpen() )
             $stream->open();
        } catch ( \Exception $e ){
            return false; }

        if( method_exists( $stream, 'isWrite' )
             && $stream->isWrite() // HAS INPUT
             && $this->position >= $this->_inputBuffer->count() ) // EXHAUSTED INPUT
            return false;

        return true;
    }

    function getStream()
    {
        if( is_null( $this->_stream ))
            $this->_stream = $this->getHandler()->getStream();

        return $this->_stream;
    }

    function getHandler()
    {
        return $this->delegate->getTarget();
    }

    function getDelegate()
    {
        return $this->delegate;
    }

    protected function setDelegate( \Core\Delegate $delegate )
    {
        $this->delegate = $delegate;
    }

    function isShared()
    {
        return $this->_shared;
    }

    function share()
    {
        $this->setShared( true );
    }

    function setShared( $bool )
    {
        $this->_shared = $bool;
    }

    function getInput()
    {
        return $this->_inputBuffer;
    }

    function setInput( $value )
    {
        if( is_array( $value ))
            $this->_inputBuffer->merge( $value );
        else
            $this->_inputBuffer->add( $value );
    }

    function getOutputBuffer()
    {
        return $this->_outputBuffer;
    }

    function setOutputBuffer( $output )
    {
        return $this->_outputBuffer = $output;
    }

    function getHandler()
    {
        return $this->_handler;
    }

    function setHandler( \IO\Filter\Handler $handler )
    {
        $this->_handler = $handler;
    }

    function setMap( $map )
    {
        if( is_null( $this->_handler ))
            $this->_handler = new \IO\Filter\Handler();

        $this->_handler->setMap( $map );
    }

    function getMap()
    {
        if( !$this->filtered() )
             return null;
        
        return $this->_handler->getMap();
    }

    function hasMap()
    {
        return !is_null( $this->_handler );
    }
}