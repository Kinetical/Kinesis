<?php
namespace IO\Stream;

use IO\Filter;

class Iterator extends \IO\Filter\Delegate\Iterator
{
    protected  $stream;
    
    private $_shared = false;

    function __construct( \Core\Delegate $delegate = null, \IO\Stream $stream = null )
    {
        $this->stream = $stream;

        parent::__construct( $delegate );
    }
        
    function rewind()
    {
        parent::rewind();

        if( !is_null( $this->stream ) &&
            method_exists( $this->stream, 'rewind') )
            $this->stream->rewind();
    }

    function valid()
    {
        if( method_exists( $this->stream, 'eof') &&
            $this->stream->eof() )
            return false;

        try {
            if( ! $this->stream->isOpen() )
              $this->stream->open();
        } catch ( \Exception $e ){
            return false; }

        return true;
    }

    function getStream()
    {
        return $this->stream;
    }

    function setStream( \IO\Stream $stream )
    {
        if( $this->delegate->isType('IO\Stream\Handler') )
            $this->delegate->getTarget()->setStream( $stream );

        $this->stream = $stream;
    }
    
    function setDelegate( \Core\Delegate $delegate )
    {
        if( is_null( $this->stream ) &&
            $delegate->isType('IO\Stream\Handler'))
            $this->stream = $delegate->getTarget()->getStream();
        
        parent::setDelegate( $delegate );
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
}