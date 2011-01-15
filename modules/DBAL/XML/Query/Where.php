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

        $params = array( 'XPath' => $this['xpath']);
        $xpath = new \DBAL\XML\Filter\Xpath( $this->getQuery(), $params );
    }

    function create($data)
    {
        $queryBuilder = $this->getQueryBuilder();

        if( $queryBuilder->hasNode('where'))
            $queryBuilder->Nodes['where']['xpath'] = '/'.$data;
        else
        {
            $this['xpath'] = $data;
            return parent::create();
        }

        return false;
    }
}
