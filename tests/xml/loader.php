<?php
use DBAL\Data;
use DBAL\XML;
use DBAL\Data\Tree;

$loader = new Data\Loader();

$params = array( 'path' => 'tests\test.xml',
                 'xpath' => '/test/subnode/child[@test="something"]');

$loader->View = new XML\View( $params );

$results = $loader();

if( $results instanceof Tree\Node )
{
    $it = new Tree\Node\Iterator( $results );
    $results = new Tree\Node\IteratorIterator( $it );
}

foreach( $results as $node )
{
    var_dump( $node->Name );
}
