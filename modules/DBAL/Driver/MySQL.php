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

        return mysql_connect( $host,
                              $user['name'],
                              $user['password'] );
    }

    function disconnect( \DBAL\Connection $conn )
    {
        return mysql_close( $conn->getLink() );
    }
}