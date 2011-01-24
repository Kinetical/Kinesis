<?php
$mapping = array( 'my*' => 'mappedMyValue',
                  'your*' => 'mappedYourValue' );

$filter = new \DBAL\Data\Mapping\Filter();
$filter->setMapping( $mapping );

$values = array('myValue','yourSomething');

foreach( $values as $key => $value )
{
    var_dump( $filter( array('input' => $value) ) );
}
