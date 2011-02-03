<?php
namespace DBAL\SQL\Query;

class Models extends Tables
{
    function create( $data )
    {
        parent::create( $data );

        $query = $this->getQuery();
        $query->Filters->register( new \DBAL\Data\Filter\Table() );

        return true;
    }
}