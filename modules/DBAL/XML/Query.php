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
        {
            $result = $this->filter( $result );
            if( is_array( $result ))
                $results->merge( $result );
            else
                $results->add( $result );
        }

        $this->setResults( $results );
        
        return $results->toArray();
    }
}
