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
        $config = $conn->getConfiguratio();
        
        $user = $config->getUser();
        
        return mysql_connect( $config['host'],
                              $user['name'],
                              $user['password'] );
        //TODO: INFORM USER OBJECT TO ERASE PASSWORD FROM MEMORY
    }

    function close( \DBAL\Connection $conn )
    {
        return mysql_close( $conn->getLink() );
    }
}