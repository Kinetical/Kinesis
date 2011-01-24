<?php
use DBAL\Data;
use DBAL\XML;

$dataSource = new Data\Source();

$dataAdapter = new Data\Adapter();

$params = array( 'path' => 'tests\test.xml' );

$dataAdapter->View = new XML\View( $params );

//$dataAdapter->Fill( $dataSource );

//var_dump( $dataSource->Data[0] );