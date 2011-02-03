<?php
include('database.php');

$query = new \DBAL\SQL\Query();
$query->build()
      ->models();

$tables = $query( $database );

foreach( $tables as $table )
{
    var_dump( $table );
}