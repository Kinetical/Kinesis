<?php
$driver = new \DBAL\Driver\MySQL();

$database = new \DBAL\Database( $driver );

$database->connect();
$database->select();

$sql = 'SELECT * FROM control';

$result = $database->query( $sql );

$delegate = new \Core\Delegate( $driver->Platform, 'fetchAssoc' );

$it = new \IO\Stream\Iterator( $delegate, $database->Connection );
$it->Shared = $driver->Platform->rowCount( $result );
$it->Input = $result;

foreach( $it as $item )
{
    var_dump( $item );
}