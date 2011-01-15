<?php
namespace DBAL\Data\Entity;

use \Core\Interfaces as I;

abstract class Relationship extends \Core\Object implements I\Nameable
{
	private $_name;

	function __construct( $name = null )
	{
		if( $name !== null )
			$this->_name = $name;

                parent::__construct();
	}

	function getName()
	{
		return $this->_name;
	}

	function setName( $name )
	{
		$this->_name = $name;
	}

	function equals( EntityRelationship $relation )
	{
		if( $this->Name === $relation->Name )
			return true;

		return false;
	}

	abstract function getQuery( Relationship\Loader\RelationshipLoader $loader );
}