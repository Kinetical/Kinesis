<?php

$driver = new \DBAL\Driver\MySQL();
$database = new \DBAL\Database( $driver );

$core->setDatabase( $database );