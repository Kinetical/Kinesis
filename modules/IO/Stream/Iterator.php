<?php
namespace IO\Stream;

class Iterator extends \Core\Object implements \Iterator
{
    private $_handler;

    private $_outputBuffer;
    private $_inputBuffer;

    private $_position = 0;

    private $_callback;

    function __construct( Handler $handler = null, $callBack = null )
    {
        $this->_handler = $handler;
        $this->_callback = $callBack;

        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();

        $this->_inputBuffer = new \Core\Collection();

        if( is_null( $this->_callback )
            && !is_null( $this->_handler ))
            $this->_callback = $this->_handler->getCallback();
        elseif( !$this->_handler->Type->hasMethod( $this->_callback ))
            throw new \IO\Exception('Handler ('.get_class( $this->_handler ).') must implement callback method('.$this->_callback.')');
    }

    function wrapped()
    {
        return !is_null( $this->_handler ) ;
    }

    protected function getInputArguments()
    {
        return $this->_inputBuffer[ $this->_position ];
    }

    function next()
    {
        $this->increment();
    }

    public function current()
    {
        $buffer = $this->_handler->{$this->_callback}( $this->getInputArguments() );

        $this->_outputBuffer = $buffer;

        return $buffer;
    }

    function key()
    {
        return $this->_position;
    }

    protected function clear()
    {
        unset($this->_outputBuffer);
    }

    function rewind()
    {
        $this->clear();
        $this->_position = 0;

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

        if( $stream->isWrite() // HAS INPUT
             && $this->_position >= $this->_inputBuffer->count() ) // EXHAUSTED INPUT
            return false;

        return true;
    }

    function getStream()
    {
        return $this->_handler->getStream();
    }

    function getHandler()
    {
        return $this->_handler;
    }

    protected function setHandler( \IO\Stream\Handler $handler )
    {
        $this->_handler = $handler;
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

    function getPosition()
    {
        return $this->_position;
    }

    protected function increment()
    {
        ++$this->_position;
    }

    protected function decrement()
    {
        --$this->_position;
    }

    protected function setPosition( $position )
    {
        $this->_position = $position;
    }

    function getCallback()
    {
        return $this->_callback;
    }

    protected function setCallback( $callBack )
    {
        $this->_callback = $callBack;
    }
}