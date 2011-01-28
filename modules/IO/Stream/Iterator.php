<?php
namespace IO\Stream;

class Iterator extends \Util\Iterator
{
    private $_outputBuffer;
    private $_inputBuffer;

    protected $delegate;

    function __construct( \Core\Delegate $delegate )
    {
        $this->delegate = $delegate;

        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();

        $this->_inputBuffer = new \Util\Collection();

        if( !$this->delegate->isType('IO\Stream\Handler' ))
            throw new \IO\Exception('Iterator delegate must be instance of IO\Stream\Handler, '.$delegate->getTargetType().' provided');
    }

    function hasDelegate()
    {
        return !is_null( $this->delegate ) ;
    }

    protected function getInputArguments()
    {
        return $this->_inputBuffer[ $this->position ];
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
        return $this->getHandler()->getStream();
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

    function getInputBuffer()
    {
        return $this->_inputBuffer;
    }

    function setInputBuffer( $value )
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