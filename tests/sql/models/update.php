<?php
$driver = new \DBAL\Driver\MySQL();
$database = new \DBAL\Database( $driver );

$core->setDatabase( $database );

$source = new \DBAL\Data\Source();

$adapter = new \DBAL\Data\Adapter();

//$adapter->View = new \DBAL\XML\View\Entity( array( 'path' => 'site\entity.xml') );

$adapter->View = new \DBAL\SQL\View\Model();

$adapter->Fill( $source );

foreach( $source as $entity )
{
    unset( $ent );
    $ent = $database->Models[ucfirst($entity->Name)];

    if( $ent instanceof \DBAL\Data\Entity )
    {
        var_dump( $ent->Name );
        var_dump( $ent->equals( $entity ));
    }
   
    //var_dump( $entity->Name );
}