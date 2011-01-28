<?php
namespace DBAL\Data\Filter;

class Collection extends \Core\Filter
{
    protected function execute( array $params )
    {
        $input = $params['input'];

        if( count( $input ) == 0 )
            return array_pop( $input );

        return $input;
    }
}