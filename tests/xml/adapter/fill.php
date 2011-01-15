<?php
use DBAL\Data;
use DBAL\XML;

$dataSource = new Data\Source();
$dataAdapter = new Data\Adapter();

$dataAdapter->View = new XML\View( 'tests\test.xml' );

$dataAdapter->Fill( $dataSource );

var_dump( $dataSource->Data[0] );