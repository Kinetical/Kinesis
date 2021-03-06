<?php
use DBAL;
use DBAL\XML;
use DBAL\Data;
use DBAL\Data\Tree;

$query = new XML\Query();

$query->build()
      ->from('tests\test.xml');

$results = $query();

var_dump( $results );

$it = new Tree\Node\Iterator( $results[0] );
$rit = new Tree\Node\IteratorIterator( $it );

foreach ( $rit as $node )
{
    var_dump( $node );
}