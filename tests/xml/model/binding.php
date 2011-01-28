<?php
use DBAL\Data\Filter;

include('loader.php');

$params = array( 'BindingProperty' => 'Name',
                 'MappingProperty' => 'Attributes');

$mapping = array( 'entity' => 'DBAL\Data\Entity',
                  'entity.name' => 'Name',
                  'entity.behavior' => 'Behaviors',
                  'attribute' => 'DBAL\Data\Entity\Attribute',
                  'attribute.name' => 'Name',
                  'attribute.type' => 'DataType',
                  'one-one' => 'DBAL\Data\Entity\Relationship\OneToOne',
                  'one-many' => 'DBAL\Data\Entity\Relationship\OneToMany',
                  'many-many' => 'DBAL\Data\Entity\Relationship\ManyToMany',
                  'many-one' => 'DBAL\Data\Entity\Relationship\ManyToOne',
                  '*-*.name' => 'Name',
                  '*-*.type' => 'Association',
                  '*-*.entity' => 'EntityName',
                  '*-*.mappedBy' => 'MappedBy',
                  '*-*.inversedBy' => 'InversedBy');

$filter = new Filter\Entity( null, $params, $mapping );

foreach( $results as $key => $value )
{
    $value = $filter( array('input' => $value) );
    if( $value instanceof \DBAL\Data\Entity )
        $entities[] = $value;
}

var_dump( $entities );