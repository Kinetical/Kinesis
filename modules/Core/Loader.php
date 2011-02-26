<?php
namespace Core;

abstract class Loader extends \Kinesis\Task
{
    protected $parameters;
    protected $manager;
    protected $cache;

    function __construct( array $params = array(), Manager $manager = null )
    {
        $this->manager = $manager;

        parent::__construct();

        $this->setParameters( $params );
        if( !is_null($cache = $this->getDefaultCache()))
        $this->setCache( $cache );
    }

    function initialise()
    {
        //parent::initialise();

        $this->parameters = new \Util\Collection();
    }

    protected function getDefaultCache()
    {
        $cacheClass = $this->parameters['CacheClass'];
        $cacheParams = $this->parameters['CacheParameters'];

        if( !is_array( $cacheParams ))
            $cacheParams = array();

        if( is_string( $cacheClass )
            && class_exists( $cacheClass ))
            return new $cacheClass( $cacheParams );
        
        return null;
    }

    function getParameters()
    {
        return $this->parameters;
    }

    function setParameters( array $params )
    {
        $this->parameters->merge( $params );
    }

    protected function caching()
    {
        return ( $this->cache instanceof Cache
                 && $this->cache->enabled() );
    }

    function getCache()
    {

        return $this->cache;
    }

    function setCache( Cache $cache )
    {
        $this->cache = $cache;
    }

    function managed()
    {
        return ($this->manager instanceof Manager);
    }

    function getManager()
    {
        return $this->manager;
    }

    function setManager( Manager $manager )
    {
        $this->manager = $manager;
    }

    abstract protected function parse( array $params = null );
    //abstract protected function execute( array $params = null );

    function __invoke( $params = null )
    {
        if( !is_null( $params )
            && !is_array( $params ))
            $params = func_get_args();
        
        return $this->execute( $this->parse( $params ) );
    }
}
