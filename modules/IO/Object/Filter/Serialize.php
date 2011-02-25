<?php
namespace IO\Object\Filter;

final class Serialize extends \IO\Filter
{
    private $_sid = 1;
    private $_sids = array();
    private $_packed = array();

    protected function execute( array $params = null )
    {
        $object = $params['input'];
        $object = $this->pack( $object );
        return $object;
    }

    private function pack( \Core\Object $object )
    {
        $this->_sid++;
        $sid = $this->_sid;
        $this->_packed[$sid] = $object;
        $this->_sids[$object->Oid] = $sid;

        
        $methods = $object->Type->getMethods( \ReflectionMethod::IS_PUBLIC );
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
                $value = $object->{$method->name}();

                if( $value instanceof \Serializable )
                {
                    if( $value instanceof \Core\Object )
                        if( array_key_exists( $value->Oid,  $this->_sids ))
                            $object->Data[$propertySetter] = new \Core\Object\Reference( $this->_sids[$value] );
                        else
                            $object->Data[$propertySetter] = $this->pack($value);
                    
                }
                elseif( !is_null( $value ) &&
                        !empty( $value ) )
                {
                    if( is_scalar( $value ) ||
                       (is_object( $value ) &&
                        $value instanceof \Serializable) )
                    {
                        $object->Data[$propertySetter] = $value;
                    }
                }
            }
            }
        }
        $object->Data['sid'] = $sid;


        return $object;
    }
}
