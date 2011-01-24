<?php
class SourceObject
{
    public $Name;
    public $SomeValue;
}

class SomeClass
{
    public $Assigned;
}

$params = array( 'BindingProperty' => 'Name' );

$mapping = array( 'some' => 'SomeClass',
                  'some.SomeValue' => 'Assigned' );

$filter = new \DBAL\Data\Binding\Filter( null, $params, $mapping );

$src = new SourceObject();
$src->Name = 'some';
$src->SomeValue = 'foobar';

$values = array( $src );

foreach( $values as $key => $value )
{
    var_dump( $filter( array('input' => $value) ) );
}
