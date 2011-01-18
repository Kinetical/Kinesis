<?php
use IO\Output;
use DBAL\XML;
use DBAL\Data\Tree;

$output = new Output\Stream();

$writer = new Output\Writer( $output );
$docWriter = new XML\Text\Writer( $writer );

$node = new Tree\Node('test');

$attr = array('test' => 'someValue',
              'attr' => 2 );

$subnode = new Tree\Node( 'subnode', $attr, $node );

$docWriter->writeDocument( $node );

echo (string)$output;