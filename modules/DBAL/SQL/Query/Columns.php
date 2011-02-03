<?php
namespace DBAL\SQL\Query;

class Columns extends \DBAL\Query\Node
{
    function create( $data )
    {
        if( $data instanceof \DBAL\Database )
            $this['database'] = $data;

        return parent::create();
    }

    function open()
    {
        $sql = 'SHOW COLUMNS ';
        if( isset( $this['database']) )
            $sql .= ' IN '.$this['database']->InnerName.' ';

        return $sql;
    }
}