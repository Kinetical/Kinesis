<?php
namespace DBAL\Platform;

class MySQL extends \DBAL\Platform
{
    function select( \DBAL\Connection $conn  )
    {
        $name = $connection->Configuration->Database['name'];

        return mysql_select_db( $name, $conn->getLink() ) ;
    }

    function query( $sql, \DBAL\Connection $conn )
    {
        return mysql_query( $sql, $conn->getLink() );
    }
}