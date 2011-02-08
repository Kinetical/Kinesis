<?php
namespace DBAL\SQL\Query;

class Tables extends \DBAL\Query\Node
{
    function create( $data )
    {
        if( $data instanceof \DBAL\Database )
            $this['database'] = $data;

        $params = array( 'StreamCallback' => 'fetchRow' );

        $query = $this->getQuery();
        $query->setParameters( $params );

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