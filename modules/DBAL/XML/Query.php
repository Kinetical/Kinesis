<?php
namespace DBAL\XML;

class Query extends \DBAL\Query
{
    function initialise()
    {
        $this->Parameters['Namespace'] = 'DBAL\XML\Query';
    }
    
    protected function execute()
    {
        $results = array();
        foreach( $this as $result )
            if( is_array( $result ))
                $results = array_merge( $results, $result );
            else
                $results[] = $result;

        return $result;
    }
}
