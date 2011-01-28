<?php
namespace DBAL\Data\Entity;

class Attribute extends \DBAL\Data\Model\Attribute
{
    const PrimaryKey = 1;
    const AutoIncrement = 2;
    const NotNull = 4;

    private $_outerName;
    private $_value;
    private $_relationship;
    private $_load;
    private $_length;

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
        return $this->getModel();
    }

    function setEntity( $entity )
    {
        $this->setModel( $entity );
    }

    function getName()
    {
        return $this->getOuterName();
    }

    function setName( $name )
    {
        $this->setOuterName( $name );
    }

    public function getOuterName()
    {
        if( is_null($this->_outerName) )
                return $this->getInnerName();

        return $this->_outerName;
    }

    public function setOuterName( $outerName )
    {
        $this->_outerName = $outerName;
    }

    function getInnerName()
    {
        return parent::getName();
    }

    function setInnerName( $name )
    {
        parent::setName( $name );
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function setValue($value)
    {
        $this->_value = $value;
    }

    function getRelation()
    {
        return $this->_relationship;
    }

    function hasRelation()
    {
        return !is_null($this->_relationship);
    }

    function setRelation( Relationship $relationship )
    {
        $this->_relationship = $relationship;
    }

    function isReference()
    {
        return !is_null($this->_relationship);
    }

    public function getLength()
    {
        //TODO: GET DEFAULT LENGTH
        return $this->_length;
    }

    public function setLength($length)
    {
        $this->_length = $length;
    }

    function equals( Attribute $attr )
    {
        if( (int)$this->Length !== (int)$attr->Length )
                return false;

        return parent::equals( $attr );
    }
}