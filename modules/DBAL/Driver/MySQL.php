<?php
namespace DBAL\Driver;

final class MySQL extends \DBAL\Driver
{
    private $errors = false;
    
    function errors()
    {
        return $this->errors;
    }
    
    function initialise()
    {
        //parent::initialize();

        $this->setPlatform( new \DBAL\Platform\MySQL() );
    }

    function connect( \DBAL\Connection $conn )
    {
        $host = $conn->Configuration->Database['host'];
        $user = $conn->Configuration->getUser();

        return mysql_connect( $host,
                              $user['name'],
                              $user['password'] );
    }

    function disconnect( \DBAL\Connection $conn )
    {
        return mysql_close( $conn->getLink() );
    }

    function select( \DBAL\Connection $conn  )
    {
        $name = $conn->Database->Name;
        
        return mysql_select_db( $name, $conn->getLink() ) ;
    }

    function query( $sql, \DBAL\Connection $conn )
    {
        $this->errors = false;
        return ($result = mysql_query( $sql, $conn->getLink() ))
                ? $result
                : $this->error( $conn );
    }
    
    function error( \DBAL\Connection $conn )
    {
        $link = $conn->getLink();
        $this->errors = true;
        return array( mysql_errno( $link ), 
                      mysql_error( $link ));
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