<?php
namespace IO;

abstract class Buffer extends \Util\Arrays\Iterator
{
    protected $mark = 0;
    protected $limit = 1024;
    protected $capacity = 1024;

    function capacity()
    {
        return $this->capacity;
    }

    function flip()
    {
        $this->Data = array_flip( $this->Data );
        $this->Keys = array_flip( $this->Keys );

        return $this;
    }

    function mark()
    {
        $this->mark = $this->position;

        return $this;
    }

    function hasRemaining()
    {
        return ( $this->remaining() > 0 )
                ? true
                : false;
    }

    function remaining()
    {
        return $this->limit - $this->position;
    }

    function limit( $limit = null )
    {
        if( is_int( $limit )
            && $limit <= $this->capacity )
        {
            if( $this->position > $limit )
                $this->position = $limit;
            if( $this->mark > $limit )
                $this->mark = 0;
            $this->limit = $limit;

            return $this;
        }
        else
            return $this->limit;
    }

    function reset()
    {
        $this->position = $this->mark;
    }

    function rewind()
    {
        parent::rewind();
        $this->mark();
    }

    function clear()
    {
        parent::clear();
        $this->limit = $this->capacity;
        $this->mark();
    }

    function position( $position = null )
    {
        if( is_int( $position ) )
        {
            if( $this->mark > $position )
                $this->mark = 0;
            
            $this->position = $position;
            return $this;
        }

        return $this->position;
    }
}
