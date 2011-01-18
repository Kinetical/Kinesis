<?php
use DBAL\XML;
use DBAL\Data\Tree;

$path = 'tests\test.xml';

$node = new Tree\Node('test');

$attr = array('test' => 'someValue',
              'attr' => 2 );

$subnode = new Tree\Node( 'subnode', $attr, $node );

$query = new XML\Query();

$query->build()
      ->update( $path )
      ->set( $node );

$query->execute();