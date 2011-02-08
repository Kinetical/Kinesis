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
        $xpath = $this->getQuery()->Map['XPath'];

        $xpath->Parameters['xpath'] = $this['xpath'];
    }

    function create($data)
    {
        $queryBuilder = $this->getQueryBuilder();

        if( $queryBuilder->hasNode('where'))
            $queryBuilder->Nodes['where']['xpath'] = $data;
        else
        {
            $this['xpath'] = $data;
            $queryBuilder->Map->register( new \DBAL\XML\Filter\Xpath() );
            return parent::create();
        }

        return false;
    }
}
