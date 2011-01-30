<?php
use DBAL\Data;
use DBAL\XML;

$loader = new Data\Loader();

$params = array( 'path' => 'site\entity.xml');

$loader->View = new XML\View\Entity( $params );

$results = $loader();

var_dump( $results );