<?php
namespace DBAL;

use \Util\Interfaces as I;

abstract class Query extends \Kinesis\Query
{
    protected $stream;
    protected $handler;
    
    function getHandler()
    {
        return $this->handler;
    }

    function setHandler( \IO\Filter\Handler $handler )
    {
        if( $this->handler instanceof IO\Filter\Handler )
            $this->handler->setMap( $handler->getMap() );
        else
            $this->handler = $handler;
    }

    function getStream()
    {
        return $this->stream;
    }

    function isRead()
    {
        if( $this->parameters->exists('StreamMode') &&
            $this->parameters['StreamMode'] == 'r')
            return true;

        $stream = $this->getStream();
        if( $stream instanceof \IO\Stream )
            return $stream->isRead();

        return false;
    }

    function isWrite()
    {
        if( $this->parameters->exists('StreamMode') &&
            $this->parameters['StreamMode'] == 'w')
            return true;

        $stream = $this->getStream();
        if( $stream instanceof \IO\Stream )
            return $stream->isWrite();

        return false;
    }

    function getDefaultStream()
    {
        $streamClass = $this->Parameters['StreamType'];
        $streamMode = $this->Parameters['StreamMode'];
        $streamResource = $this->Parameters['StreamResource'];

        if( class_exists( $streamClass ))
            return new $streamClass( $streamResource, $streamMode );

        //TODO: THROW EXCEPTION
    }

    function setStream( \IO\Stream $stream )
    {
        $this->stream = $stream;
    }
    
    function resolve()
    {
        if( !is_null( $this->Stream ) )
        {
            if( !$this->stream->isOpen() )
            {
                try
                    { $this->stream->open(); }
                catch( \Exception $e )
                    { return false; }
            }
        }
        else
            return false;

        return true;
    }

    protected function getDefaultDelegate()
    {
        $streamHandler = $this->Parameters['StreamHandler'];
        $streamCallback = $this->Parameters['StreamCallback'];

        if( class_exists( $streamHandler ))
            $handler = new $streamHandler( $this->stream );

        $handlers = $this->Parameters['HandlerChain'];
        if( !is_array( $handlers ))
            $handlers = array( $handlers );

        if( count( $handlers ) > 0 )
            foreach( $handlers as $wrapClass )
                if( class_exists( $wrapClass ))
                    $handler = new $wrapClass( $handler );

        return new \Core\Delegate( $handler, $streamCallback );
    }

    function getDefaultIterator()
    {
        $streamInput = $this->Parameters['StreamInput'];

        $delegate = $this->getDefaultDelegate();

        $iterator = new \IO\Stream\Iterator( $delegate );

        if( $this->handler instanceof \IO\Filter\Handler )
            $iterator->setHandler( $this->handler );

        if( !is_null( $streamInput ))
            $iterator->setInput( $streamInput );

        return $iterator;
    }

    function setIterator( \IO\Stream\Iterator $iterator )
    {
        if( is_null( $this->stream ))
            $this->setStream( $iterator->getStream() );

        $this->iterator = $iterator;
    }
}