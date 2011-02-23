<?php
namespace DBAL\XML\Query;

class Set extends \Kinesis\Task\Statement
{
    function __construct( $input, Task $parent = null )
    {
        parent::__construct( array( 'Input' => $input ), $parent );
    }
    
    function execute()
    {
        $input = $this->Parameters['Input'];
        $params = array( 'StreamInput' => $input );

        if( $input instanceof \DBAL\XML\Document )
            $params['StreamCallback'] = 'writeDocument';
        elseif( $input instanceof \DBAL\Data\Tree\Node )
            $params['StreamCallback'] = 'writeNodes';
        
        $this->getQuery()->Parameters += $params;
    }
}