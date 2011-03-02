<?php
namespace DBAL\Data\Model;

use \Util\Interfaces as I;

abstract class Attribute extends \Kinesis\Object implements I\Nameable
{
    public $Name;
    public $DataType;
    private $_dataType;
    private $_flags;
    private $_default;
    private $_model;

    function __construct( $name = null, $type = null )
    {
        $this->setInnerName( $name );
        $this->setDataType( $type );

        $args = func_get_args();
        array_shift( $args );
        array_shift( $args );

        $flags = $args;
        $this->setFlags( $flags );

        parent::__construct();
    }

    function equals( EntityAttribute $attr )
    {
        if( $this->Name == $attr->Name
                && (string)$this->TypeName == (string)$attr->TypeName
                && $this->Default == $attr->Default
                && $this->hasFlags( $attr->Flags ) )
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

    public function getDataType()
    {
        if( is_string($this->DataType) )
        {//TODO: $type = $this->getModel()->getDataSet()->getTypeLoader()
            //$typeLoader = \Core::getInstance()->getDatabase()->getDataSet()->getTypeLoader();
            //$this->DataType = $typeLoader->loadType( $this );
        }

        return $this->DataType;
    }

    function getTypeName()
    {
        return (string)$this->DataType;
    }

    public function setDefault($default)
    {
        $this->_default = $default;
    }

    function setFlags(array $flags)
    {
        foreach( $flags as $flag )
             $this->AddFlag( $flag );
    }

    public function setInnerName($innerName)
    {
        $this->setName( $innerName );
    }

    function setName( $name )
    {
        $this->Name = $name;
    }

    public function setDataType($type)
    {
        $this->DataType = $type;
    }
}