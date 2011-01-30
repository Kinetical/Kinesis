<?php
namespace DBAL\Platform;

class MySQL extends \DBAL\Platform
{
    function select( \DBAL\Connection $conn  )
    {
        $name = $conn->Database->Name;

        return mysql_select_db( $name, $conn->getLink() ) ;
    }

    function query( $sql, \DBAL\Connection $conn )
    {
        return mysql_query( $sql, $conn->getLink() );
    }

    function fetchRow( $result )
    {
        return mysql_fetch_row( $result );
    }

    function fetchAssoc( $result )
    {
        return mysql_fetch_assoc( $result );
    }

    function fetchArray( $result )
    {
        return mysql_fetch_array( $result );
    }

    function rowCount( $result )
    {
        return mysql_num_rows( $result );
    }
}