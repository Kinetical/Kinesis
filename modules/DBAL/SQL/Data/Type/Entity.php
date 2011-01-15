<?php
namespace DBAL\SQL\Data\Type;

//TODO: CHANGE ENTITYTYPE TO SQLENTITYTYPE
class Entity extends \DBAL\SQL\Data\Type\Integer
{
	private $_entity;

	function __construct( $entityName )
	{
		$this->Entity = $entityName;
		parent::__construct();
	}

	function setEntity( $entity )
	{
		$this->_entity = $entity;
	}

	function getEntity()
	{
		if( is_string( $entity ))
		{
                    $dataSet = \Core::getInstance()->getDatabase()->getDataSet();

			if( $dataSet->hasSchematic( $entity ))
				$this->_entity = $dataSet->Schematics[ $entity ];
		}

		return $this->_entity;
	}
}
