<?php
namespace IO\Stream;

class Iterator extends \Util\Iterator
{
    private $_outputBuffer;
    private $_inputBuffer;
    private $_stream;
    private $_shared = false;

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

        return $this->_inputBuffer[ $position ];
    }

    public function current()
    {
        $delegate = $this->delegate;

        $buffer = $delegate( $this->getInputArguments() );

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
}