<?php
namespace DBAL\XML\Query;

class OrAttribute extends Attribute
{
    function open()
    {
        $queryBuilder = $this->getQueryBuilder();
        if( count( $queryBuilder->Nodes['where']->getChildren() ) > 1 )
            $queryBuilder->Nodes['where']['xpath'] .= ' or ';

        parent::open();
    }
}