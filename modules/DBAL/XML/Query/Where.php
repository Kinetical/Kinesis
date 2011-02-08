<?php
namespace DBAL\XML\Query;

class Where extends \DBAL\Query\Node
{
    function open()
    {
        if( $this->hasChildren() )
        {
            $this['xpath'] .= '[';
            $this->openChildren();
            $this['xpath'] .= ']';
        }

        $query = $this->getQuery();
        $query->setParameters( array('xpath' => $this['xpath'] ));
    }

    function create($data)
    {
        $queryBuilder = $this->getQueryBuilder();

        if( $queryBuilder->hasNode('where'))
            $queryBuilder->Nodes['where']['xpath'] = $data;
        else
        {
            $this['xpath'] = $data;
            return parent::create();
        }

        return false;
    }
}
