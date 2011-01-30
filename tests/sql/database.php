<?php
$config = new \DBAL\Configuration();
//var_dump( $config->getUser() );
//var_dump( $config->User['name']);
//var_dump( $databaseConfig );


    $driver = new \DBAL\Driver\MySQL();
    $conn = new \DBAL\Connection( $driver, $config );
    $dataBase = new \DBAL\Database( $conn );

    $conn->open();

    //var_dump( $driver->Platform->select( $conn ) );

    $sql = 'SHOW TABLES IN netolith';

    $result = mysql_query( $sql );

    while( $value = mysql_fetch_row( $result ) )
    {
        var_dump( $value[0] );
    }


    
//    $connection =  new \DBAL\Connection\SQLConnection
//                         ( $databaseConfig['host'],
//                           new \DBAL\User\SQLUser
//                                    ( $userConfig['name'],
//                                      $userConfig['password']));
//    $connection->Connect();
//    $dataBase->setConnection( $connection );
//    $dataBase->Select();
//
//    $this->setDatabase( $dataBase );

