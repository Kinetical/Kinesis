<?php
namespace DBAL\XML\Query;

class AndAttribute extends Attribute
{
    function open()
    {
        if( $this->QueryBuilder->Nodes['where']->Children[0]->Oid !== $this->Oid )
            $this->QueryBuilder->Nodes['where']['xpath'] .= ' and ';
        
        parent::open();
    }
}