<?php
namespace DBAL\Data\Entity\Relationship\Loader;

class Deferred extends \DBAL\Data\Entity\Relationship\Loader
{
	private $_relation;

	function __construct( $object, $relation = null )
	{
		if( $object instanceof EntityRelationship )
			$this->_relation = $object;
		else
			$this->setSource( $object );

		if( $relation instanceof EntityRelationship )
			$this->_relation = $relation;
		elseif( is_string($relation)
			 && $object->Type->isPersisted()
			 && $object->Type->isPersistedBy('EntityObject')
			 && $object->Type->getPersistenceObject()->hasRelation( $relation ) )
			$this->_relation = $object->Type->getPersistenceObject()->Relations[$relation];
	}

	function getRelation()
	{
		return $this->_relation;
	}

	function setRelation( EntityRelationship $relation )
	{
		$this->_relation = $relation;
	}

	function getOwningEntity()
	{
		if( $this->Relation->isLocalReference() )
			return $this->Relation->Entity;

		return $this->Relation->Association;
	}


	function load( $path, $args = null )
	{
		$query = $this->Relation->getQuery( $this );

		if( $query == null )
			return null;

		return $query->execute();
	}
}