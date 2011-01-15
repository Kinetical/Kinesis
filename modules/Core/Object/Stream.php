<?php
namespace Core\Object;

class Stream extends \IO\Serial\Loader
{
    function readObject()
    {
        $object = parent::readObject();

        $object = $this->unpack( $object );
        $this->restore();

        return $object;
    }

    protected function unpack( $object )
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
                    if( $value instanceof \Core\Object )
                    $value = $this->unpack( $value );
                    
                    if( method_exists( $object, $setter ))
                        call_user_func( array( $object, $setter), $value );
            }

            
            

                //$object->{$setter}($value);
        }

        \Core::getInstance()->getLoader()->add( $this );



        return $object;
    }

    private function restore()
    {
        if( is_array( $this->_references ))
        {
            foreach( $this->_references as $object => $data )
            {
                list( $setter, $reference ) = $data;
                $value = $this->_unpacked[ $reference->ID ];
                $object->{$setter}( $value );
            }
        }

    }

    private $_sid = 1;
    private $_sids;
    private $_packed;
    private $_unpacked;
    private $_references;

    protected function pack( \Core\Object $object )
    {
        $this->_sid++;
        $sid = $this->_sid;
        $this->_packed[$sid] = $object;
        $this->_sids[$object->Oid] = $sid;
       // echo get_class( $object );
                //echo '<br/>-';
        //var_dump( get_class( $object ));

        $rc = new \ReflectionClass( $object );
        $methods = $rc->getMethods( \ReflectionMethod::IS_PUBLIC );
        foreach( $methods as $method )
        {
            if( $method->name == 'getData' )
                continue;
            if( substr( $method->name, 0, 3) == 'get' )
            {
                $methodName = substr( $method->name, 3 );
                $propertySetter = 'set'.$methodName;
            if( method_exists( $object, $propertySetter)  )
            {
                
               // echo $propertySetter;
                //echo '<br/>';
                $value = $object->{$method->name}();
                //var_dump( $value );
                if( $value instanceof \Core\Object )
                    if( array_key_exists( $value->Oid,  $this->_sids ))
                        $object->Data[$propertySetter] = new \Core\Object\Reference( $this->_sids[$value] );
                    else
                        $object->Data[$propertySetter] = $this->pack($value);
                elseif(  !is_null( $value )
                        && !empty( $value ))
                    $object->Data[$propertySetter] = $value;
            }
            }
        }
        $object->Data['sid'] = $sid;
        

        return $object;
    }

    function writeObject( \Core\Object $object )
    {
        $object = $this->pack( $object );
        parent::writeObject( $object );
    }


}