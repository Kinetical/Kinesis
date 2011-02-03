<?php
namespace DBAL\Data\Filter;

class Table extends \Core\Filter
{
    function execute( array $params = array() )
    {
        $table = $params['input'];

        $query = new \DBAL\SQL\Query();
        $query->build()
              ->attributes()
              ->from( $table );

        $attributes = $query();

        return new \DBAL\Data\Entity( $table, $attributes );
    }
}
