<?php
use DBAL\Data;
use DBAL\XML;

$loader = new Data\Loader();

$params = array( 'path' => 'site\entity.xml',
                 'xpath' => 'entity[@name="Control"]');

$loader->View = new XML\View\Entity( $params );

$results = $loader();

var_dump( $results );