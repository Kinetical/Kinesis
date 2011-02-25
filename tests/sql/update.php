<?php

include('database.php');

$data = array( 'name' => 'newww',
               'parent' => '20',
               'type' => 'Page',
               'properties' => null ) ;

$query = new \DBAL\SQL\Query();
$query->build()
      ->update( $database->Models['Control'] )
      ->set( $data )
      ->where('id', '5');

$query();