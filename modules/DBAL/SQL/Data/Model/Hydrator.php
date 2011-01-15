<?php
namespace DBAL\SQL\Data\Model;

class Mapper extends \Core\Component\Mapper
{
	const CHANGE = 1;
	const ADD = 2;
	const DROP = 3;
	const JOINTABLE = 4;

	private $_changes;

	function __construct()
	{

	}

	function map( SqlEntity $entity , SqlEntity $changes )
	{
		// TODO: MOVE TO CORRESPONDING ATTRIBUTE AND RELATIONSHIP MAPPER
		// MAP ATTRIBUTES

		$foundAttributes = array();
		$this->_changes = array();
		foreach( $entity->Attributes as $name => $attr )
		{
			if( !is_string( $name ))
				$name = $attr->InnerName;

			$changeAttr = null;
			if( array_key_exists( $name, $changes->Attributes ) )
				//&& !$changes->Attributes[ $name ]->isReference() )
			{
				$changeAttr = $changes->Attributes[ $name ];
				$foundAttributes[ $name ] = $changedAttr;
			}
			//else
				// TODO: CHECK DIRTY NAME ON CHANGED ATTRIBUTE FOR MATCH WITH OLD ATTRIBUTE

			$changedAttribute = $this->mapAttribute( $attr, $changeAttr );
			if( $changedAttribute !== null )
				$entity->Attributes[ $name ] = $changedAttribute;
		}

		$newAttributes = array_diff_key( $changes->Attributes, $foundAttributes );

		foreach( $newAttributes as $attr )
		{
			$entity->Attributes[ $attr->InnerName ] = $this->mapAttribute( null, $attr );
		}

		// MAP RELATIONS
		$relationshipMapper = new SQLRelationshipMapper();
		$entity = $relationshipMapper->map( $entity );

		$foundRelations = array();
		foreach( $entity->Relations as $name => $relation )
		{
			if( !is_string( $name ))
				$name = $relation->Name;

			$changeRelation = null;
			if( array_key_exists( $name, $changes->Relations ) )
			{
				$changeRelation = $changes->Relations[ $name ];
				$foundRelations[ $name ] = $changeRelation;
			}

			$changedRelation = $this->mapRelation( $relation, $changeRelation );
			if( $changedRelation !== null )
				$entity->Relations[ $name ] = $changeRelation;
		}

		$newRelations = array_diff_key( $changes->Relations, $foundRelations );

		foreach( $newRelations as $relation )
		{
			$entity->Relations[ $relation->Name ] = $this->mapRelation( null, $relation );
		}

		return $entity;
	}

	function mapRelation( SQLRelationship $relation = null , SQLRelationship $changes = null )
	{

		if( $relation == null
			&& $changes instanceof ManyToManyRelationship ) // || JOINTABLERELATIONSHIP
		{
                    $dataSet = \Core::getInstance()->getDatabase()->getDataSet();
			//$entityManager = EntityManager::getInstance();

			$name = $changes->Association->InnerName.'_'.$changes->Entity->InnerName;
			if( !$dataSet->hasSchematic( $name ))
			{
				$jointEntity = new SqlEntity( $name );
				$jointEntity->addAttribute( new SQLAttribute($changes->Association->InnerName,'integer') );
				$jointEntity->addAttribute( new SQLAttribute($changes->Entity->InnerName,'integer') );

				$this->_changes[][ SqlSchemaMapper::JOINTABLE ] = $jointEntity;
			}
			else
				$this->_changes[][ SqlSchemaMapper::JOINTABLE ] = $name;

			return $changes;
		}
		elseif( $changes == null
				&& $relation instanceof ManyToManyRelationship )
		{
			$name = $relation->Association->InnerName.'_'.$relation->Entity->InnerName;
			//$entityManager = EntityManager::getInstance();
                        $dataSet = \Core::getInstance()->getDatabase()->getDataSet();
			if( !$dataSet->hasSchematic( $name ) )
			{

			}
			//TODO: REMOVE JOINT TABLE

			return $relation;
		}

		if( $changes == null
			&& $relation->Entity->hasAttribute( $relation->Name ) ) //REMOVE
		{
			$this->_changes[][ SqlSchemaMapper::DROP ] = $relation->Entity->Attributes[ $relation->Name ];
			return null;
		}

		if( (	$relation instanceof SQLRelationship
				&& $relation->isLocalReference()
				&& $relation->Entity->hasAttribute( $relation->Name )
				) || (
				$changes instanceof SQLRelationship
				&& $changes->isLocalReference()
				&& $changes->Entity->hasAttribute( $relation->Name )
				)
			 )
			{

				if( $relation == null ) // ADD
				{
					$changeAttr = $changes->Entity->Attribute[ $changes->Name ];
					$this->_changes[][ SqlSchemaMapper::ADD ] = $changeAttr;
					return $changes;
				}
				else // CHANGE
				{
					$attr = $relation->Entity->Attributes[ $relation->Name ];
					$changeAttr = $changes->Entity->Attributes[ $changes->Name ];

					if( $this->hasChanges( $attr, $changeAttr ))
					{
						$this->_changes[][ SqlSchemaMapper::CHANGE ] = array( $attr->Name, $changeAttr);
						return $changes;
					}
				}
			}
		return $relation;
	}

	function mapAttribute( SQLAttribute $attr = null, SQLAttribute $changes = null ) // EITHER ATTRIBUTE OR RELATION ARGS
	{
		if( $changes == null ) //REMOVE
		{
			$this->_changes[][ SqlSchemaMapper::DROP ] = $attr;
			return null;
		}

		if( $attr == null ) // ADD
		{
			$this->_changes[][ SqlSchemaMapper::ADD ] = $changes;
			return $changes;
		}


		if( $this->hasChanges( $attr, $changes )) // CHANGE
		{
			$this->_changes[][ SqlSchemaMapper::CHANGE ] = array( $attr->InnerName, $changes);
			return $changes;
		}

		return $attr;
	}

	function getChanges()
	{
		return $this->_changes;
	}

	protected function setChanges( $changes )
	{
		$this->_changes = $changes;
	}

	function hasChanges( $attr = null, $changes = null ) // EITHER ATTRIBUTE OR RELATION ARGS
	{
		if( $attr == null
			&& $changes == null )
			return ($this->_changes !== null) ? true : false;

		if( $attr->equals( $changes ))
			return false;

		return true;
	}
}