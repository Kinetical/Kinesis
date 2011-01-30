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

        $this->results->clear();

        foreach( $this as $result )
        {
            $result = $this->filter( $result );
            
            if( is_array( $result ))
                $this->results->merge( $result );
            else
                $this->results->add( $result );
        }

        return $this->results->toArray();
    }
}
