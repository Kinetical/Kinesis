<?php
namespace IO\Stream;

class Iterator extends \Core\Object implements \Iterator
{
    private $_wrapper;

    private $_outputBuffer;
    private $_inputBuffer;

    private $_position = 0;

    private $_callback;

    function __construct( Wrapper $wrapper = null, $callBack = null )
    {
        $this->_wrapper = $wrapper;
        $this->_callback = $callBack;

        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();

        if( is_null( $this->_callback )
            && !is_null( $this->_wrapper ))
            $this->_callback = $this->_wrapper->getCallback();
        elseif( !$this->_wrapper->Type->hasMethod( $this->_callback ))
            throw new \IO\Exception('Wrapper('.get_class( $this->_wrapper ).') must implement callback method('.$this->_callback.')');
    }

    function wrapped()
    {
        return ( $this->_wrapper !== null ) 
                ? true
                : false;
    }

    protected function getInputArguments()
    {
        if( is_array( $this->_inputBuffer )
            && array_key_exists( $this->_position, $this->_inputBuffer  ))
            $args = $this->_inputBuffer[ $this->_position ];
        else
            $args = $this->_inputBuffer;

        return $args;
    }

    function next()
    {
        $this->increment();
    }

    public function current()
    {
        var_dump('current');
        $buffer = $this->_wrapper->{$this->_callback}( $this->getInputArguments() );

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
        var_dump('test');
        $stream = $this->getStream();

        if( $stream->eof() )
            return false;

        try
        {
            if( !$stream->isOpen() )
             $stream->open();
        } catch ( \Exception $e ){
            return false;
        }

        return true;
    }

    function getStream()
    {
        return $this->_wrapper->getStream();
    }

    function getWrapper()
    {
        return $this->_wrapper;
    }

    protected function setWrapper( \IO\Stream\Wrapper $wrapper )
    {
        $this->_wrapper = $wrapper;
    }

    function getInputBuffer()
    {
        return $this->_inputBuffer;
    }

    function setInputBuffer( $value )
    {
        return $this->_inputBuffer = $value;
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