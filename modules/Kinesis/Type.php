<?php
namespace Kinesis;

class Type
{
    private static $types = array();
    private static $instances = array();

    private $id;

    public $Name;
    public $Parent;
    public $Parameters;

    function __construct()
    {
        if( func_num_args() > 0 )
        {
            $args = func_get_args();
            foreach( $args as $value )
            {
               if( is_string( $value ))
               {
                   if( array_key_exists( $parent, self::$types ) )
                   {
                       $this->Parent = self::$instances[ self::$types[ $parent ] ];
                   }
                   else
                   {
                       $this->Name = $value;
                   }
               }
               elseif( is_object( $value ) )
               {
                   $this->Parent = $value;
               }
               elseif( is_array( $value ))
               {
                   $this->Parameters = $value;
               }
            }
        }
        

        $this->id = \spl_object_hash( $this );

        self::$instances[ $this->id ] = $this;
        self::$types[ __CLASS__ ] = $this->id;
    }

}