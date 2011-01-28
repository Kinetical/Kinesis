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

        $params = array( 'xpath' => $this['xpath']);

        $xpath = $this->getQuery()->Filters['XPath'];

        $xpath->setParameters( $params );
    }

    function create($data)
    {
        $queryBuilder = $this->getQueryBuilder();

        if( $queryBuilder->hasNode('where'))
            $queryBuilder->Nodes['where']['xpath'] = '/'.$data;
        else
        {
            $this['xpath'] = $data;
            $queryBuilder->Filters->register( new \DBAL\XML\Filter\Xpath() );
            return parent::create();
        }

        return false;
    }
}
