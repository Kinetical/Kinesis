<?php
use DBAL\XML\Data\Tree;

$node = new Tree\Node('test');

$attr = array('test' => 'someValue',
              'attr' => 2 );

new Tree\Node( 'subnode', null, $node );
new Tree\Node( 'subnode', $attr, $node );
new Tree\Node( 'subnode', null, $node );
new Tree\Node( 'subnode', $attr, $node );

var_dump( $node('subnode[@test="someValue"]') );