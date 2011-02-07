<?php
$driver = new \DBAL\Driver\MySQL();
$database = new \DBAL\Database( $driver );

$core->setDatabase( $database );

$xmlSource = new \DBAL\Data\Source();
$sqlSource = new \DBAL\Data\Source();

$adapter = new \DBAL\Data\Adapter();

$adapter->View = new \DBAL\XML\View\Entity();
$adapter->Fill( $xmlSource );

$adapter->View = new \DBAL\SQL\View\Model();
$adapter->Fill( $sqlSource );

var_dump( $xmlSource->Data );

$adapter->Update( $sqlSource );
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