<?php
namespace IO\Stream;

use IO\Filter;

class Iterator extends \IO\Filter\Delegate\Iterator
{
    private $_stream;
    private $_shared = false;

    function __construct( \Core\Delegate $delegate = null, \IO\Stream $stream = null )
    {
        $this->_stream = $stream;

        parent::__construct( $delegate );
    }
    
    function isFiltered( $state = Filter::OUTPUT )
    {
        if( $this->hasMap() &&
            $state == \IO\Filter::INPUT )
            if( $this->isShared() &&
                $this->position < 1 )
                return true;
            else
                return true;

        return parent::isFiltered( $state );
    }
    
    function rewind()
    {
        parent::rewind();

        $stream = $this->getStream();
        if( !is_null( $stream ) &&
            $stream->Type->hasMethod('rewind'))
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
             && parent::valid() ) // EXHAUSTED INPUT
            return false;

        return true;
    }

    function getStream()
    {
        if( is_null( $this->_stream ))
        {
            $target = $this->getTarget();

            if( $target instanceof \IO\Stream\Handler )
                $this->_stream = $target->getStream();
        }

        return $this->_stream;
    }

    function setStream( \IO\Stream $stream )
    {
        $target = $this->getTarget();

        if( $target instanceof \IO\Stream\Handler )
            $target->setStream( $stream );

        $this->_stream = $stream;
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