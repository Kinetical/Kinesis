<?php
namespace DBAL\Data\Entity;

abstract class Attribute extends \DBAL\Data\Model\Attribute
{
    const PrimaryKey = 1;
    const AutoIncrement = 2;
    const NotNull = 4;

    private $_outerName;
    private $_value;
    private $_relationship;
    private $_entity;
    private $_load;

    function getLoadName()
    {
        return $this->_load;
    }

    function setLoadName( $key )
    {
        $this->_load = $key;
    }

    function IsPrimaryKey()
    {
            return $this->HasFlag( self::PrimaryKey );
    }

    function IsAutoIncrement()
    {
            return $this->HasFlag( self::AutoIncrement );
    }

    function IsNotNull()
    {
            return $this->HasFlag( self::NotNull );
    }

    function getEntity()
    {
        $dataSet = \Core::getInstance()->getDatabase()->getDataSet();
            if( is_string( $this->_entity )
                  && $dataSet->hasSchematic( $this->_entity ))
                    $entity = $dataSet->Schematics[$this->_entity];
            if( $entity !== null )
                    $this->_entity = $entity;

            return $this->_entity;
    }

    function setEntity( $entity )
    {
            $this->_entity = $entity;
    }

    public function getOuterName()
    {
            if( is_null($this->_outerName) )
                    return $this->getInnerName();

            return $this->_outerName;
    }

    public function getValue() {
            return $this->_value;
    }

    public function setOuterName( $outerName )
    {
            $this->_outerName = $outerName;
    }

    public function setValue($value) {
            $this->_value = $value;
    }

    function getRelation()
    {
            return $this->_relationship;
    }

    function hasRelation()
    {
            return ($this->_relationship !== null) ? true : false;
    }

    function setRelation( EntityRelationship $relationship )
    {
            $this->_relationship = $relationship;
    }

    function isReference()
    {
            return ($this->_relationship !== null) ? true : false;
    }

    function serialize()
    {
        $this->Data['load'] = $this->getLoadName();
        $this->Data['outerName'] = $this->getOuterName();
        $this->Data['relation'] = $this->getRelation();
        $this->Data['value'] = $this->getDefault();
        
        return parent::serialize();
    }
}