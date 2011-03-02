<?php
namespace DBAL\Data\Entity;

use \Util\Interfaces as I;

abstract class Relationship extends \Kinesis\Object implements I\Nameable
{
    const OneToOne='OneToOne';
    const OneToMany='OneToMany';
    const ManyToMany='ManyToMany';
    const ManyToOne='ManyToOne';

    protected $entity;

    public $Name;
    public $EntityName;
    
    private $_association;
    private $_mappedBy;  // OWNING SIDE
    private $_inversedBy;// INVERSED SIDE
    private $_entityName;
    
    function __construct( $name = null, \DBAL\Data\Entity $entity = null )
    {
        $this->Name = $name;
        if( !is_null( $entity ))
            $this->setEntity($entity);
        
        parent::__construct();
    }

    function getEntity()
    {
        //TODO: entity by entityname if blank
        return $this->entity;
    }

    function setEntity( \DBAL\Data\Entity $entity )
    {
        $this->entity = $entity;
    }

    function setEntityName( $name )
    {
        $this->EntityName = $name;
    }

    function getEntityName()
    {
        // TODO: from $this->entity if entityName is blank
        return $this->EntityName;
    }

    function getName()
    {
            return $this->Name;
    }

    function setName( $name )
    {
            $this->Name = $name;
    }

    function equals( Entity\Relationship $relation )
    {
            if( $this->Name === $relation->Name )
                    return true;

            return false;
    }

    function getMappedBy()
    {
            return $this->_mappedBy;
    }

    function setMappedBy( $name )
    {
            $this->_mappedBy = $name;
    }

    function getInversedBy()
    {
            return $this->_inversedBy;
    }

    function setInversedBy( $name )
    {
            $this->_inversedBy = $name;
    }

    function getAssociation()
    {
            return $this->_association;
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
            if( $this instanceof Relationship\ManyToMany )
                    return true;

            if( $this instanceof Relationship\ManyToOne )
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

    abstract function getQuery( Relationship\Loader\RelationshipLoader $loader );
}