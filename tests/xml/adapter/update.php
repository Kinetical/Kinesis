<?php
use DBAL\Data;
use DBAL\XML;
use DBAL\XML\Data\Tree;

$dataSource = new Data\Source();
$dataAdapter = new Data\Adapter();

$node = new Tree\Node('test');
$subnode = new Tree\Node( 'subnode', array('test' => 'someValue',
                                           'attr' => 2 ), $node );
$dataSource->add( $node );

$dataAdapter->View = new XML\View( 'tests\test.xml' );

$dataAdapter->Update( $dataSource );

var_dump( $dataSource );