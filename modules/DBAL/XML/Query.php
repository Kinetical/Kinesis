<?php
namespace DBAL\XML;

class Query extends \DBAL\Query
{
    function initialize()
    {
        parent::initialize();
        $this->setFormat( self::XML );
    }

    function execute( $stream = null )
    {
        if( ($this->resolve( $stream )) == false )
            return null;

        $results = $this->getResults();
        $results->clear();

        foreach( $this as $result )
            $results->add( $this->filter( $result ) );

        $this->setResults( $results );
        
        return $results->toArray();
    }
}
