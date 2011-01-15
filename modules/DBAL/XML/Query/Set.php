<?php
namespace DBAL\XML\Query;

class Set extends \DBAL\Query\Node
{
    function create($data)
    {
        $params = array( 'StreamInput' => $data );

        $query = $this->getQuery();
        $query->setParameters( $params );
        return parent::create();
    }
}