<?php
namespace DBAL\Data\Model;

use \Util\Interfaces as I;

abstract class Attribute extends \Kinesis\Parameter implements I\Nameable
{
    private $flags;
    private $default;
    private $model;

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
        return $this->model;
    }

    function setModel( \DBAL\Data\Model $model )
    {
        $this->model = $model;
    }

    function addFlag( $flag )
    {
        if( !is_null($flag ) )
            $this->flags[ $flag ] = $flag;
    }

    function hasFlag( $flagName )
    {
        if( !is_array( $this->flags ))
            return false;
        if( is_array( $flagName ))
            return $this->hasFlags( $flagName );

        return array_key_exists( $flagName, $this->flags );
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
        return $this->default;
    }

    public function getFlags()
    {
        if( !is_array($this->flags) )
            $this->flags = array();
        return $this->flags;
    }

    function getName()
    {
        return $this->Name;
    }

    function getTypeName()
    {
        return (string)$this->Type;
    }

    public function setDefault($default)
    {
        $this->default = $default;
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