<?php
use DBAL;
use DBAL\XML;
use DBAL\Data;
use DBAL\Data\Tree;

$path = 'tests\test.xml';

$node = new Tree\Node('test');

$query = new XML\Query();

$query->build()
      ->update( $path )
      ->set( $node );

$query->execute();