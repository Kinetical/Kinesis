<?php
namespace DBAL;

use \Util\Interfaces as I;

abstract class Query extends \Core\Object implements \IteratorAggregate, I\Executable
{
    const SQL = 'SQL';
    const XML = 'XML';
    const INI = 'ini';
    const CSV = 'csv';

    protected $parameters;
    protected $results;
    protected $builder;
    protected $stream;
    
    private $_iterator;

    function __construct( array $params = array() )
    {
        parent::__construct();

        $this->setParameters( $params );
    }

    function initialize()
    {
        if( is_null( $this->results ) )
            $this->results = new Query\Result( $this );

        $this->parameters = new \Util\Collection();

        parent::initialize();
    }

    function isRead()
    {
        $stream = $this->getStream();
        if( $stream instanceof \IO\Stream )
            return $stream->isRead();

        return false;
    }

    function isWrite()
    {
        $stream = $this->getStream();
        if( $stream instanceof \IO\Stream )
            return $stream->isWrite();

        return false;
    }

    function getParameters()
    {
        return $this->parameters;
    }

    function setParameters( array $params )
    {
        $this->parameters->merge( $params );
    }

    function getDataType()
    {
        return $this->parameters['DataType'];
    }

    function setDataType( $type )
    {
        $this->parameters['DataType'] = $type;
    }

    function getFormat()
    {
        return $this->parameters['Format'];
    }

    function setFormat( $type )
    {
        $this->parameters['Format'] = $type;
    }

    function getIterator()
    {
        if( is_null( $this->_iterator ) )
            $this->_iterator = $this->getDefaultIterator();
        
        return $this->_iterator;
    }

    protected function getDefaultIterator()
    {
        $stream = $this->getStream();

        $streamHandler = $this->parameters['StreamHandler'];
        $streamCallback = $this->parameters['StreamCallback'];
        $streamInput = $this->parameters['StreamInput'];

        if( class_exists( $streamHandler ))
            $handler = new $streamHandler( $stream );
        else
            throw new DBAL\Exception('StreamHandler('.$streamHandler.') not found');

        $handlers = $this->parameters['HandlerChain'];
        if( !is_array( $handlers ))
            $handlers = array( $handlers );

        if( count( $handlers ) > 0 )
            foreach( $handlers as $wrapClass )
                if( class_exists( $wrapClass ))
                    $handler = new $wrapClass( $handler );

        $delegate = new \Core\Delegate( $handler, $streamCallback );
        
        $iterator = new \IO\Stream\Iterator( $delegate );

        if( !is_null( $streamInput ))
            $iterator->setInput( $streamInput );

        return $iterator;
    }
    
    function setIterator( \IO\Stream\Iterator $iterator )
    {
        if( is_null( $this->stream ))
            $this->setStream( $iterator->getStream() );
        
        $this->_iterator = $iterator;
    }
    
    function setResults( $data )
    {
        return $this->results = $data;
    }

    function hasResult()
    {
        return isset( $this->results );
    }

    function getResults()
    {
        return $this->results;
    }

    function build()
    {
        if( is_null( $this->builder ) )
            $this->builder =  new Query\Builder( $this );

        return $this->builder;
    }

    function setStream( \IO\Stream $stream )
    {
        $this->stream = $stream;
    }

    function getStream()
    {
        if( is_null( $this->stream ) )
            $this->stream = $this->getDefaultStream();

        return $this->stream;
    }

    function getDefaultStream()
    {
        $streamClass = $this->parameters['StreamType'];
        $streamMode = $this->parameters['StreamMode'];
        $streamResource = $this->parameters['StreamResource'];

        if( class_exists( $streamClass ))
            return new $streamClass( $streamResource, $streamMode );
        else
            throw new DBAL\Exception ('StreamType('.$streamClass.') not found');
    }

    protected function resolve( $stream = null )
    {
        if( is_null( $stream ) )
            $stream = $this->getStream();

        if( !is_null( $stream ) )
        {
            if( !$stream->isOpen() )
            {
                try
                    { $stream->open(); }
                catch( \Exception $e )
                    { return false; }
            }
        }
        else
            return false;
            

        $this->setStream( $stream );

        if( $this->_iterator instanceof IO\Stream\Iterator &&
            $this->_iterator->wrapped() )
            $this->_iterator->getHandler()->setStream( $stream );

        return true;
    }

    abstract protected function execute( $stream );

    function __invoke( $stream = null )
    {
        if( ($builder = $this->builder) instanceof Query\Builder )
        {
            unset( $this->builder );
            $results = $builder( $stream );
            $this->builder = $builder;

            return $results;
        }

        if( ($this->resolve( $stream )) == false )
            return null;

        if( is_null( $stream ))
            $stream = $this->getStream();

        return $this->execute( $stream );
    }
}