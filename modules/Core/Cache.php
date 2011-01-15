<?php
namespace Core;

abstract class Cache extends Collection
{
    private $_enabled = true; //TODO: MOVE TO CONFIG FILE
    protected $_callback;

    function __construct( $callback = null )
    {
        $this->_callback = $callback;
        parent::__construct();
    }

    function enable()
    {
        $this->_enabled = true;
    }

    function disable()
    {
        $this->_enabled = false;
    }

    function enabled()
    {
        return $this->_enabled;
    }
    //BLOCK INTROSPECTION ON CACHE DATA IN OBJECT MODEL

    function __set( $key, $value )
    {
        //MEMORY CACHE
        $this->Data[$key] = $value;

        // FUNCTION FOR EXTERNAL CACHING, I.E. SERIALLOADER
        if( !is_null( $this->_callback )
            && $this->enabled() )
        {
            try
            {
                call_user_func_array( $this->_callback, array( $key, $value ));
            }
            catch( Exception $e )
            {
                throw new \Core\Exception('Caching failed for keypair ('.$key.','.$value.')');
            }
        }
    }
}
