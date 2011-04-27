<?php
namespace Util\Collection;

class Dictionary extends \Util\Collection
{
    private $_type = null;

    function __construct( array $data = array(), $type = null )
    {
        if( !is_null( $type ))
            $this->setDataType( $type );
        
        parent::__construct( $data );
    }

    function setDataType( $type )
    {
        if( class_exists( $type )
            || interface_exists( $type ))
            $this->_type = $type;
        else
            throw new \Core\Exception( 'Invalid data type('.$type.')' );
    }

    function getDataType()
    {
        if( $this->isStronglyTyped() )
                return $this->_type;

        return false;
    }
    
    function isStronglyTyped()
    {
        return !is_null($this->_type);
    }

    function isWeaklyTyped()
    {
        return !$this->isStronglyTyped();
    }

    function offsetSet( $key, $value )
    {
        if( $this->isStronglyTyped()
            && !($value instanceof $this->_type ))
        {
            throw new \Core\Exception('DataArray does not accept items of type: '. get_class( $value ));
        }

        parent::offsetSet( $key, $value );
    }
}