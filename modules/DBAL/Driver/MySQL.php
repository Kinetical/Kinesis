<?php
namespace DBAL\Driver;

class MySQL extends \DBAL\Driver
{
    function initialize()
    {
        parent::initialize();

        $this->setPlatform( new \DBAL\Platform\MySQL() );
    }

    function connect( \DBAL\Connection $conn )
    {
        $host = $conn->Configuration->Database['host'];
        $user = $conn->Configuration->getUser();

        //var_dump( $user );
        
        return mysql_connect( $host,
                              $user['name'],
                              $user['password'] );
        //TODO: INFORM USER OBJECT TO ERASE PASSWORD FROM MEMORY
    }

    function close( \DBAL\Connection $conn )
    {
        return mysql_close( $conn->getLink() );
    }
}