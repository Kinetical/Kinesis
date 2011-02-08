<?php
namespace DBAL\XML;

class Query extends \DBAL\Query
{
    function initialize()
    {
        parent::initialize();
        $this->setFormat( self::XML );
    }

    protected function execute( $stream )
    {
        $this->results->clear();

        foreach( $this as $result )
            if( is_array( $result ))
                $this->results->merge( $result );
            else
                $this->results->add( $result );

        return $this->results->toArray();
    }
}
