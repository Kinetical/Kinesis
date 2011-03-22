<?php
use DBAL as DB;
// MODEL BUILDER TEST
// Model builder modifies tables and column definitions in the database
/* EXAMPLE XML ENTITY

<entity name="Clothing" type="Product">
    <attribute name="Color" type="string">None</attribute>
</entity>
 
*/
// ESTABLISH DATABASE
$driver = new DB\Driver\MySQL();
$database = new DB\Database( $driver );
$core->setDatabase( $database );

// DATA COMPONENTS
$source = new DB\Data\Source();
$adapter = new DB\Data\Adapter();

// RETRIEVE XML ENTITY DATA
$adapter->View = new DB\XML\View\Entity();
$xmlEntities = $adapter->Fill( $source );

// RETRIEVE SQL ENTITY DATA
$adapter->View = new DB\SQL\View\Entity();
$sqlEntities = $adapter->Fill( $source );

// CREATE MODELER
$query = new DB\SQL\Query();
$query->setBuilder( new DBAL\SQL\Modeler( array() ) );

// SPECIFY THE ENTITIES TO COMPARE
$query->build()
      ->update( $sqlEntities )
      ->set( $xmlEntities );

// CREATES/ALTERS/DROP SQL TABLES OR COLUMNS
// COMPARE XML TO CURRENT SCHEMA AND GENERATE DIFFERENCE
// GENERATE TRANSACTION TO UPDATE SCHEMA BASED ON DIFFERENCES
$query();

//foreach( $source as $entity )
//{
//    unset( $ent );
//    $ent = $database->Models[ucfirst($entity->Name)];
//
//    if( $ent instanceof DB\Data\Entity )
//    {
//        var_dump( $ent->Name );
//        var_dump( $ent->equals( $entity ));
//    }
//
//    //var_dump( $entity->Name );
//}