<?php
namespace IO\Object\Filter;

final class Unserialize extends \IO\Filter
{
    private $_unpacked = array();
    private $_references = array();

    protected function execute( array $params = null )
    {
        $object = $params['input'];
        
        $object = $this->unpack( $object );
        $this->restore();

        return $object;
    }

    private function unpack( $object )
    {

        $this->_unpacked[$object->Data['sid']] = $object;
        $data = $object->Data;

        $object->Data = array();

        foreach( $data as $setter => $value )
        {
            if( $value instanceof \Core\Object\Reference )
            {
                $this->_references[ $object ] = array( $setter, $value );
            }
            elseif( substr( $setter, 0, 3 ) == 'set' )
            {
                    if( $value instanceof \Kinesis\Object )
                    $value = $this->unpack( $value );

                    if( method_exists( $object, $setter ))
                        $object->$setter( $value );

            }
            elseif( $setter !== 'sid' )
            {
                $object->Data[ $setter ] = $value;
            }
        }

        //TODO: ADD TO GLOBAL OBJECT CACHE
        //\Core::getInstance()->getLoader()->add( $this );

        return $object;
    }

    private function restore()
    {
        foreach( $this->_references as $object => $data )
        {
            list( $setter, $reference ) = $data;
            $value = $this->_unpacked[ $reference->ID ];
            $object->{$setter}( $value );
        }
    }
}