<?php
namespace DBAL\SQL\Query;

class Tables extends \DBAL\Query\Node
{
    function create( $data )
    {
        if( $data instanceof \DBAL\Database )
            $this['database'] = $data;

        $query = $this->getQuery();

        $query->Parameters['StreamCallback'] = 'fetchRow';

        $query->Filters->register( new \DBAL\Data\Filter\Scalar() );

        return parent::create();
    }

    function open()
    {
        $sql = 'SHOW TABLES';
        if( isset( $this['database']) )
            $sql .= ' IN '.$this['database']->Name;

        return $sql;
    }
}