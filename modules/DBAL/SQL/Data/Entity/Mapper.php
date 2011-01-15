<?php
namespace DBAL\SQL\Data\Entity;

class Mapper extends \DBAL\Data\Entity\Mapper
{
	function initialize()
	{
		/*
		 * <entity name="myTable">
		 * 	<attribute name="myColumn" type="string">defaultValue</attribute>
		 *  ...
		 * </entity>
		 * ...
		 * array( SQLEntity , ... )
		 * array( array( SQLAttribute , ... ) , ... )
		*/
                
		$this->setMapping( array(
                                            'entity' => 'ORM\Entity\SQLEntity',
                                            'entity.name' => 'OuterName',
                                            'entity.namespace' => 'Namespace',
                                            'entity.behavior' => 'Behaviors',
                                            'entity.loader' => 'LoaderName',
                                            'attribute' => 'ORM\Entity\SQLAttribute',
                                            'attribute.name' => 'OuterName',
                                            'attribute.type' => 'DataType',
                                            'attribute.load' => 'LoadName',
                                            'one-one' => 'ORM\Entity\Relationship\OneToOneRelationship',
                                            'one-many' => 'ORM\Entity\Relationship\OneToManyRelationship',
                                            'many-many' => 'ORM\Entity\Relationship\ManyToManyRelationship',
                                            'many-one' => 'ORM\Entity\Relationship\ManyToOneRelationship',
                                            '*-*.name' => 'Name',
                                            '*-*.type' => 'Association',
                                            '*-*.entity' => 'Entity',
                                            '*-*.mappedBy' => 'MappedBy',
                                            '*-*.inversedBy' => 'InversedBy'

                                            ) );
                parent::initialize();
	}

	function map( \Web\UI\DocumentElement $node )
	{
		return parent::map( $node );
	}


	protected function mapData( $mappedObject, \Core\Object $subject )
	{
		return parent::mapData( $mappedObject, $subject );
	}
}