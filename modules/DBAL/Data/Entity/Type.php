<?php
namespace DBAL\Data\Entity;

class Type extends \DBAL\SQL\Type\Integer
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

			if( $dataSet->hasModel( $entity ))
				$this->_entity = $dataSet->Models[ $entity ];
		}

		return $this->_entity;
	}
}
