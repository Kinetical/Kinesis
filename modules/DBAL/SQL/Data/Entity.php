<?php
namespace \DBAL\SQL\Data;

class Entity extends \DBAL\Data\Entity
{
	private $_outerName;

	function getOuterName()
	{
		if( $this->_outerName == null )
			$this->_outerName = ucfirst( $this->getInnerName() );

		return $this->_outerName;
	}

	function getInnerName()
	{
		if( parent::getInnerName() == null )
			return strtolower($this->_outerName); // TODO: APPEND DATABASE PREFIX, SET IN DATABASE CONFIG

		return parent::getInnerName();
	}

	function setOuterName( $name )
	{
		$this->_outerName = $name;
	}

	public function getName()
	{
            if( $this->isQualified() )
                $name = $this->getNamespace().'\\';

            return $name.$this->getOuterName();
	}

	function relatedTo( $entity )
	{
		$relations = $this->getRelationsTo( $entity );
		if( count( $relations ) > 0 )
			return true;

		return false;
		//TODO: this
	}

	function getRelationsTo( $entity )
	{
		$relations = array();
		foreach( $this->Relations as $relation )
			if( $relation->getEntity()->getName()  == $entity->getName() )
				$relations[ $relation->getName() ] = $relation;

		//TODO: this
		/*
		 * foreach entity->relations
		 * if this->hasAttribute( relation->MappedBy )
		 * 	$relations[] = relation;
		 */

		return $relations;

	}
}