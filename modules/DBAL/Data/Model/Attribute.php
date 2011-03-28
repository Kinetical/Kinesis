<?php
namespace DBAL\Data\Model;

use \Util\Interfaces as I;

abstract class Attribute extends \Kinesis\Parameter implements I\Nameable
{
    private $_dataType;
    private $_flags;
    private $_default;
    private $_model;

    function __construct( $name = null, $type = null )
    {
        parent::__construct( $name, $type );

        $args = func_get_args();
        array_shift( $args );
        array_shift( $args );

        $flags = $args;
        $this->setFlags( $flags );
    }

    function equals( Attribute $attribute )
    {
        if( parent::equals( $attribute ) &&
            $this->getDefault() == $attribute->getDefault() &&
            $this->hasFlags( $attribute->getFlags() ) )
            return true;

        return false;
    }

    function getModel()
    {
        return $this->_model;
    }

    function setModel( \DBAL\Data\Model $model )
    {
        $this->_model = $model;
    }

    function addFlag( $flag )
    {
        if( !is_null($flag ) )
            $this->_flags[ $flag ] = $flag;
    }

    function hasFlag( $flagName )
    {
        if( !is_array( $this->_flags ))
            return false;
        if( is_array( $flagName ))
            return $this->hasFlags( $flagName );

        return array_key_exists( $flagName, $this->_flags );
    }

    function hasFlags( array $flags )
    {
        foreach( $flags as $name )
            if( !$this->hasFlag( $name ))
                return false;

        return true;
    }

    public function getDefault()
    {
        return $this->_default;
    }

    public function getFlags()
    {
        if( !is_array($this->_flags) )
            $this->_flags = array();
        return $this->_flags;
    }

    function getName()
    {
        return $this->Name;
    }

    public function getType()
    {
        if( is_string($this->Type) )
        {//TODO: $type = $this->getModel()->getDataSet()->getTypeLoader()
            //$typeLoader = \Core::getInstance()->getDatabase()->getDataSet()->getTypeLoader();
            //$this->Type = $typeLoader->loadType( $this );
        }

        return $this->Type;
    }

    function getTypeName()
    {
        return (string)$this->Type;
    }

    public function setDefault($default)
    {
        $this->_default = $default;
    }

    function setFlags(array $flags)
    {
        foreach( $flags as $flag )
             $this->addFlag( $flag );
    }

    public function setInnerName($innerName)
    {
        $this->setName( $innerName );
    }

    function setName( $name )
    {
        $this->Name = $name;
    }

    public function setType($type)
    {
        $this->Type = $type;
    }
}