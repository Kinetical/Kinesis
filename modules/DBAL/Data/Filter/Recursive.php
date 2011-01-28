<?php
namespace DBAL\Data\Filter;

class Recursive extends Handler
{
    protected function execute( array $params = array() )
    {
        $input = $params['input'];
        $output = parent::execute( $params );

        foreach( $input as $child )
            $this->execute( array( 'input' => $child, 'parent' => $output ) );

        return $output;
    }
}