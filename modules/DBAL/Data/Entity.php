<?php
namespace DBAL\Data;

class Entity extends Model
{
    protected $relations;
    protected $behaviors = array();
    protected $alias;

    private $_key;

    function initialize()
    {
        $this->relations = new Entity\Relationship\Collection( $this );
        $this->attributes = new Entity\Attribute\Collection( $this );
        parent::initialize();
    }

    function hasBehavior( $behavior )
    {
        return array_key_exists( $behavior, $this->behaviors );
    }

    function getKey()
    {
        if( $this->_key instanceof Entity\Attribute )
            return $this->_key;

        foreach( $this->attributes as $attr )
            if( $attr->HasFlag( Entity\Attribute::PrimaryKey ) )
                return $attr;

        return null;
    }

    function setKey( Entity\Attribute $attr )
    {
        $this->_key = $attr;
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
        return $this->behaviors;
    }

    function setAlias($alias)
    {
        $this->alias = $alias;
    }

    function setRelations( array $relations )
    {
        $this->relations->merge( $relations );
    }

    function setBehaviors( $behaviors )
    {
        if( is_string( $behaviors ))
            $behaviors = explode(' ', str_replace(',',' ',$behaviors) );
        if( is_array( $behaviors ))
            foreach( $behaviors as $behavior )
                $this->behaviors[ $behavior ] = $behavior;
        else
            $this->behaviors = $behaviors;
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
        foreach( $this->relations as $relation )
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