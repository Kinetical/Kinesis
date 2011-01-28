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

class OtherClass
{
    public $Assigned;
}

$params = array( 'BindingProperty' => 'Name' );

$mapping = array( 'some' => 'SomeClass',
                  'some.*Value' => 'Assigned',
                  'other' => 'OtherClass',
                  'other.*Value' => 'Assigned');

$filter = new \DBAL\Data\Binding\Filter( null, $params, $mapping );

$values = array();

$src = new SourceObject();
$src->Name = 'some';
$src->SomeValue = 'foo';

$values[] = $src;

$src = new SourceObject();
$src->Name = 'other';
$src->SomeValue = 'bar';

$values[] = $src;

foreach( $values as $key => $value )
{
    var_dump( $filter( array('input' => $value) ) );
}
