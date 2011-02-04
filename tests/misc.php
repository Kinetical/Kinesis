<?php
$driver = new \DBAL\Driver\MySQL();
$database = new \DBAL\Database( $driver );

$core->setDatabase( $database );

var_dump( $database->Models['Clothing']);

var_dump( $database->Models['Address']);