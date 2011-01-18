<?php
namespace DBAL\XML\Query;

class Set extends \DBAL\Query\Node
{
    function create($data)
    {
        $params = array( 'StreamInput' => $data );

        if( $data instanceof \DBAL\XML\Document )
            $params['StreamCallback'] = 'writeDocument';
        elseif( $data instanceof \DBAL\Data\Tree\Node )
            $params['StreamCallback'] = 'writeNodes';

        $query = $this->getQuery();
        $query->setParameters( $params );
        return parent::create();
    }
}