<?php
use DBAL\Data\Binding;

class SourceObject
{
    public $SomeValue;
}

class SomeClass
{
    public $Assigned;
}

$mapping = array( 'SourceObject' => 'SomeClass',
                  'SourceObject.*Value' => 'Assigned' );

$filter = new Binding\Filter();
$filter->Mapping = $mapping;

$src = new SourceObject();
$src->SomeValue = 'foobar';

$values = array( $src );

foreach( $values as $key => $value )
{
    var_dump( $filter( array('input' => $value) ) );
}
