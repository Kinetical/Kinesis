<?php
$driver = new \DBAL\Driver\MySQL();
$database = new \DBAL\Database( $driver );

$core->setDatabase( $database );

$source = new \DBAL\Data\Source();

$adapter = new \DBAL\Data\Adapter();



$adapter->View = new \DBAL\XML\View\Entity();
$adapter->Fill( $source );

$adapter->View = new \DBAL\SQL\View\Entity();
$adapter->Fill( $source );

$adapter->Update( $source );
//foreach( $source as $entity )
//{
//    unset( $ent );
//    $ent = $database->Models[ucfirst($entity->Name)];
//
//    if( $ent instanceof \DBAL\Data\Entity )
//    {
//        var_dump( $ent->Name );
//        var_dump( $ent->equals( $entity ));
//    }
//
//    //var_dump( $entity->Name );
//}