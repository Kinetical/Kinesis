<?php
namespace DBAL\Data;

abstract class Entity extends Model
{
	const TimeStamp = 1;
	const NestedSet = 2;

	private $_alias;
	
	private $_relationships;
	private $_behaviors;


	private $_primaryKey;

	function __construct( $innerName = null, $alias = null )
	{
		$this->setAlias( $alias );
		parent::__construct( $innerName );
	}

	function addAttribute( $attribute )
	{
		$attribute->setEntity( $this );
		if( $attribute->HasFlag( EntityAttribute::PrimaryKey ))
			$this->_primaryKey = $attribute->getInnerName();
                parent::addAttribute( $attribute );
	}



	function hasAttribute( $name )
	{
		return parent::hasAttribute( $name );
	}

	function addRelation( EntityRelationship $relationship  )
	{
		$this->_relationships[ $relationship->getName() ] = $relationship;
	}

	function hasRelation( $name )
	{
		$exists = (array_key_exists( $name, $this->_relationships )) ? true : false;
		if( ! $exists
			&& is_array( $this->_attributes ))
			foreach( $this->_attributes as $attr )
				if( $attr->hasRelation()
					&& $attr->getRelation()->getName() == $name )
					return true;

		return $exists;
	}

	function addBehavior( $behavior )
	{
		$this->_behaviors[ $behavior ] = $behavior;
	}

	function hasBehavior( $behavior )
	{
		return array_key_exists( $behavior, $this->_behaviors );
	}

	function getPrimaryKey()
        {
		if( is_string( $this->_primaryKey )
			&& $this->hasAttribute( $this->_primaryKey ) )
			return $this->getAttribute( $this->_primaryKey );

                $attributes = $this->getAttributes();

		if( is_array( $attributes ))
			foreach( $attributes as $attr )
				if( $attr->HasFlag( EntityAttribute::PrimaryKey ) )
					return $attr;

		// NO PRIMARY KEY;
                return null;
	}

	function setPrimaryKey( $attr )
	{
		if( is_string( $attr )
			&& array_key_exists( $attr, $this->_attributes ))
			$this->_primaryKey = $attr;

		if( $attr instanceof EntityAttribute  )
			$this->_primaryKey = $attr->getInnerName();
	}

	function getIndex()
	{
		if( !$this->hasIndex() )
		{
			$query = \ORM\Query::build( \ORM\Query::HYDRATE_SCALAR )
                                      ->select('MAX('.$this->getPrimaryKey()->getInnerName().')')
                                      ->from( $this );

			parent::setIndex( $query->execute() );
		}

		return parent::getIndex();
	}

	function setIndex( $idx )
	{
		if( !$this->hasIndex() )
			$this->getIndex();

		parent::setIndex( $idx );
	}

	public function getAlias() {
		if( $this->_alias == null )
			return str_replace(array('a','e','i','o','u'), '', $this->getInnerName()).'_';
		return $this->_alias;
	}

	public function getAttributes() {
		return parent::getAttributes();
	}

	function getRelations()
	{
		return $this->_relationships;
	}


	function getBehaviors()
	{
		return $this->_behaviors;
	}
	

	function setAlias($alias) {
		$this->_alias = $alias;
	}

	function setAttributes( array $attributes, $append = false ) {
		if( !$append )
			parent::clearAttributes();

		parent::setAttributes( $attributes );
	}

	function setRelations( $relations, $append = false )
	{
		if( !$append )
			$this->_relationships = array();

		foreach( $relations as $relation )
			$this->_relationships[ $relation->getName() ] = $relation;
	}

	function setBehaviors( $behaviors, $append = false )
	{
		if( !$append )
			$this->_behaviors = array();

		if( is_array( $behaviors ))
			foreach( $behaviors as $behavior )
				$this->addBehavior( $behavior );
		else
			$this->_behaviors = $behaviors;
	}

        function serialize()
        {
            $this->Data['behaviors'] = $this->getBehaviors();
            $this->Data['relations'] = $this->getRelations();
            return parent::serialize();
        }
}