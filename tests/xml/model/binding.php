<?php
use DBAL\Data\Filter;

include('loader.php');

$filter = new Filter\Entity();

foreach( $results as $key => $value )
{
    $value = $filter( array('input' => $value) );
    if( $value instanceof \DBAL\Data\Entity )
        $entities[] = $value;
}

var_dump( $entities );