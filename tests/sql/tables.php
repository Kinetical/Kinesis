<?php
include('database.php');

$query = new \DBAL\SQL\Query();
$query->build()
      ->tables();

$tables = $query( $database );

foreach( $tables as $table )
{
    var_dump( $table );
    $query = new \DBAL\SQL\Query();
    $query->build()
          ->columns()
          ->from( $table );

    $columns = $query( $database );
    var_dump( $columns );
}