<?php
use DBAL\Data;
use DBAL\XML;
use DBAL\Data\Tree;

$loader = new Data\Loader();

$params = array( 'path' => 'site\entity.xml',
                 'xpath' => 'core/' );

$loader->View = new XML\View( $params );

$results = $loader();

var_dump( $results );

if( $results instanceof Tree\Node )
{
    $it = new Tree\Node\Iterator( $results );
    $results = new Tree\Node\IteratorIterator( $it );
}

foreach( $results as $node )
{
    //var_dump( $node->Name );
}
