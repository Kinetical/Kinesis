<?php
namespace DBAL\Platform;

class MySQL extends \DBAL\Platform
{
    function select( \DBAL\Connection $connection  )
    {
        $config = $connection->getConfiguration();
        $name = $config['database/name'];

        mysql_select_db( $name, $connection->getLink() ) ;
    }

    function query( $sql, \DBAL\Connection $conn )
    {
        return mysql_query( $sql, $conn->getStream()->getResource() );
    }
}