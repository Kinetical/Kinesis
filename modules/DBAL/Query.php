<?php
namespace DBAL;

abstract class Query extends \Core\Object implements \IteratorAggregate
{
    const SQL = 'SQL';
    const XML = 'XML';
    const INI = 'ini';
    const CSV = 'csv';

    protected $parameters;
    protected $results;
    
    private $_builder;
    private $_stream;
    private $_iterator;
    private $_filters;

    function __construct( array $params = array() )
    {
        parent::__construct();

        $this->setParameters( $params );
    }

    function initialize()
    {
        if( $this->results == null )
            $this->results = new Query\Result( $this );

        $this->parameters = new \Util\Collection();
        $this->_filters = new \Core\Filter\Chain();

        parent::initialize();
    }

    function isRead()
    {
        $stream = $this->getStream();

        return $stream->isRead();
    }

    function isWrite()
    {
        $stream = $this->getStream();

        return $stream->isWrite();
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
        if( $this->_iterator == null )
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
        $iterator->setInputBuffer( $streamInput );

        return $iterator;
    }
    
    function setIterator( \IO\Stream\Iterator $iterator )
    {
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
        if( $this->_builder == null )
            $this->_builder =  new Query\Builder( $this );

        return $this->_builder;
    }

    function __toString()
    {
        if( $this->_text == null
            && $this->_builder instanceof DBAL\Query\Builder )
            $this->_text = $this->_builder->build();

        return $this->_text;
    }

    function setStream( \IO\Stream $stream )
    {
        $this->_stream = $stream;
    }

    function getStream()
    {
        if( $this->_stream == null )
            $this->_stream = $this->getDefaultStream();

        return $this->_stream;
    }

    function getDefaultStream()
    {
        $streamClass = $this->parameters['StreamType'];
        $streamMode = $this->parameters['StreamMode'];
        $streamResource = $this->parameters['StreamResource'];

        //$test = new \IO\File\Stream();
        if( class_exists( $streamClass ))
            return new $streamClass( $streamResource, $streamMode );
        else
            throw new DBAL\Exception ('StreamType('.$streamClass.') not found');
    }

    protected function resolve( \IO\Stream $stream = null )
    {
        if( $stream == null )
            $stream = $this->getStream();

        if( $stream !== null
            && !$stream->isOpen() )
        {
            try
                { $stream->open(); }
            catch( \Exception $e )
                { return false; }
        }
        else
            return false;

        $this->setStream( $stream );

        if(    $this->_iterator instanceof IO\Stream\Iterator
            && $this->_iterator->wrapped() )
               $this->_iterator->getHandler()->setStream( $stream );

        return true;
    }

    function getFilters()
    {
        return $this->_filters;
    }

    function setFilters( \Core\Filter\Chain $filters )
    {
        $this->_filters = $filters;
    }

    function hasFilters()
    {
        return ($this->_filters->count() > 0 )
                ? true
                : false;
    }

    protected function filter( $input, array $params = null )
    {
        if( !$this->hasFilters() )
            return $input;

        if( is_null( $params ))
            $params = array();

        $params['input'] = $input;

        foreach( $this->_filters as $filter )
        {
            if( !is_null( $output ))
                $params['input'] = $output;

            if( is_array( $output ))
                foreach( $output as $key => $value )
                    $output[$key] = $filter( array('input' => $value ) );
            else
                $output = $filter( $params );
        }

        return $output;
    }

    abstract protected function execute( $stream = null );

    function __invoke( $stream = null )
    {
        if( ($builder = $this->_builder) instanceof Query\Builder )
        {
            $this->_builder = null;
            $results = $builder( $stream );
            $this->_builder = $builder;

            return $results;
        }

        return $this->execute( $stream );
    }
}