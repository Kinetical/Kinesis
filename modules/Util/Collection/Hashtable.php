<?php
namespace Util\Collection;

class Hashtable extends \Util\Collection
{
    function insert( $key, $value )
    {
        parent::insert( $this->hash( $key ), $value );
    }

    protected function hash( $key )
    {
        return md5( $key );
    }
}
