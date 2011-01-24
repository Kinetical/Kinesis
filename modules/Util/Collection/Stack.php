<?php
namespace Util\Collection;

class Stack extends \Util\ArrayList
{
    function peek()
    {
        return $this->Data[ $this->count()-1 ];
    }

    function push( $item )
    {
        return array_push( $this->Data, $item );
    }

    function pop()
    {
        return array_pop( $this->Data );
    }

    function search( $item )
    {
        return array_search( $this->Data, $item );
    }
}
