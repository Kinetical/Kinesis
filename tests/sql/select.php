<?php

include('database.php');

$query = new \DBAL\SQL\Query();
$query->build()
      ->select('*')
      ->from($database->Models['Control']);

$results = $query( $database );

foreach( $results as $item )
{
    var_dump( $item );
}