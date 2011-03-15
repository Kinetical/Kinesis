<?php
namespace IO\Filter;

class Recursive extends Handler
{
    protected function execute( array $params = array() )
    {
        $input = $params['input'];
        $state = $params['state'];
        $output = parent::execute( $params );

        $children = array();

        foreach( $input as $child )
            $children[] = $this->execute( array( 'input' => $child, 'parent' => $output, 'state' => $state ) );

        if( count( $children ) > 0 )
        {
            if( is_null( $output ))
                return $children;
            elseif(method_exists( $output, 'setChildren'))
                $output->setChildren( $children );
        }

        return $output;
    }
}