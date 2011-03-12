<?php
namespace DBAL\Data;

use \Util\Interfaces as I;

class Entity extends Model
{
    public $Relations;
    public $Behaviors = array();
    protected $alias;

    private $_key;
    
    function __construct( $name = null, array $attributes = array() )
    {
        $this->Relations = new Entity\Relationship\Collection( $this );
        parent::__construct( $name, $attributes );
    }

    function hasBehavior( $behavior )
    {
        return array_key_exists( $behavior, $this->Behaviors );
    }

    function getKey()
    {
        if( $this->_key instanceof Entity\Attribute )
            return $this->_key;

        foreach( $this->Attributes as $attr )
            if( $attr->HasFlag( Entity\Attribute::PrimaryKey ) )
                return $attr;

        return null;
    }

    function setKey( Entity\Attribute $attr )
    {
        $this->_key = $attr;
    }
    
    function hasAlias()
    {
        return !is_null( $this->alias );
    }

    public function getAlias()
    {
        if( is_null( $this->alias ) )
            return str_replace(array('a','e','i','o','u'), '', $this->getName()).'_';

        return $this->alias;
    }

    function getRelations()
    {
        return $this->relations;
    }

    function getBehaviors()
    {
        return $this->Behaviors;
    }

    function setAlias($alias)
    {
        $this->alias = $alias;
    }

    function setRelations( array $relations )
    {
        $this->Relations->merge( $relations );
    }

    function setBehaviors( $behaviors )
    {
        if( is_string( $behaviors ))
            $behaviors = explode(' ', str_replace(',',' ',$behaviors) );
        if( is_array( $behaviors ))
            foreach( $behaviors as $behavior )
                $this->Behaviors[ $behavior ] = $behavior;
        else
            $this->Behaviors = $behaviors;
    }

    function relatedTo( $entity )
    {
        $relations = $this->getRelationsTo( $entity );
        if( count( $relations ) > 0 )
            return true;

        return false;
    }

    function getRelationsTo( $entity )
    {
        $relations = array();
        foreach( $this->Relations as $relation )
            if( $relation->getEntity()->getName()  == $entity->getName() )
                $relations[ $relation->getName() ] = $relation;

        /*
         * foreach entity->relations
         * if this->hasAttribute( relation->MappedBy )
         * 	$relations[] = relation;
         */

        return $relations;
    }
}