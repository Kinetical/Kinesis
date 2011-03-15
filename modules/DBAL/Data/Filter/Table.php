<?php
namespace DBAL\Data\Filter;

class Table extends \IO\Filter
{
    function execute( array $params = array() )
    {
        $table = $params['input'][0];

        $dataSource = new \DBAL\Data\Source;
        $adapter = new \DBAL\Data\Adapter;
        $adapter->View = new \DBAL\SQL\View\Attributes( array('Table' => $table ));
        
        $adapter->Fill( $dataSource );
        
        $attributes = $dataSource->Data;

        return new \DBAL\Data\Entity( $table, $attributes );
    }
}
