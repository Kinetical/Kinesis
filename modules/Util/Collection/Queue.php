<?php
namespace Util\Collection;

class Queue extends \Util\ArrayList
{
    function peek()
    {
        if( $this->count() == 0 )
            return null;

        return $this[0];
    }
    
    function enqueue( $item )
    {
        $this[] = $item;
    }

    function dequeue()
    {
        if( $this->count() == 0 )
            return null;
        
        return array_shift( $this );
    }
}