<?php
namespace DBAL\SQL\Data\Entity;

abstract class Relationship extends \DBAL\Data\Entity\Relationship
{
	const OneToOne='OneToOne';
	const OneToMany='OneToMany';
	const ManyToMany='ManyToMany';
	const ManyToOne='ManyToOne';

	private $_association;
	private $_entity;

	private $_mappedBy;  // OWNING SIDE
	private $_inversedBy;// INVERSED SIDE

	function getEntity()
	{
		if( is_string( $this->_entity ))
                {
			$dataSet = \Core::getInstance()->getDatabase()->getDataSet();

			if( $dataSet->hasSchematic( $entity ))
				$this->_entity = $dataSet->Schematics[ $entity ];
                }
		if( $entity !== null )
			$this->_entity = $entity;

		return $this->_entity;
	}

	function getMappedBy()
	{
		return $this->_mappedBy;
	}

	function getInversedBy()
	{
		return $this->_inversedBy;
	}

	function getAssociation()
	{
		return $this->_association;
	}

	function setEntity( $entity )
	{
		$this->_entity = $entity;
	}

	function setMappedBy( $name )
	{
		$this->_mappedBy = $name;
	}

	function setInversedBy( $name )
	{
		$this->_inversedBy = $name;
	}

	function setAssociation( $association )
	{
		$this->_association = $association;
	}

	function isBidirectional()
	{
		if( $this->_inversedBy !== null
			&& $this->_mappedBy !== null )
			return true;

		return false;
	}

	function isUnidirectional()
	{
		if( $this->_mappedBy !== null
			&& $this->_inversedBy == null )
			return true;

		return false;
	}

	function isLocalReference()  // OWNING SIDE
	{
		if( $this instanceof ManyToManyRelationship )
			return true;



		if( $this instanceof ManyToOneRelationship )
			if(	$this->_association->hasRelation( $this->getName() )
				&& $this->_inversedBy !== null )
				return true;
			else
				return false;


		if( $this->_mappedBy == null )
			return true;


		return false;
	}

	function isForeignReference() // INVERSE SIDE
	{
		// TODO: ON MAP OF XML SCHEMA RECOGNIZE INVERSE SIDE AND CREATE INVERSE PROPERTY ON FOREIGN ENTITY AUTOMAGICALLY
		return !$this->isLocalReference();

	}
}