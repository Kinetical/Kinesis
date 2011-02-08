<?php
namespace DBAL\SQL\Query;

class Attributes extends Columns
{
    function create( $data )
    {
        $query = $this->getQuery();

        $query->Map->register( new \DBAL\Data\Filter\Column() );

        return parent::create( $data );
    }
}