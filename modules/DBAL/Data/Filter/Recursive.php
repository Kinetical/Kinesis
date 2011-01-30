<?php
namespace DBAL\Data\Filter;

class Recursive extends Handler
{
    protected function execute( array $params = array() )
    {
        $input = $params['input'];
        $output = parent::execute( $params );

        $children = array();

        foreach( $input as $child )
            $children[] = $this->execute( array( 'input' => $child, 'parent' => $output ) );

        if( is_null( $output ) &&
            count( $children ) > 0 )
        {
            //var_dump( $children );
            return $children;
        }

        return $output;
    }
}