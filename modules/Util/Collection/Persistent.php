<?php
namespace Util\Collection;

class Persistent extends \Util\Collection
{
    protected $dirty = false;
    protected $owner;
    protected $snapshot = array();

    function getSnapshot()
    {
        return $this->snapshot;
    }

    function hasSnapshot()
    {
        return !empty($this->snapshot);
    }

    function setSnapshot( array $array )
    {
        $this->snapshot = $array;
    }

    function getChanges()
    {
        return array_diff( $this->snapshot, $this->Data );
    }

    function snapshot()
    {
        $this->snapshot = $this->Data;
    }

    function rollback()
    {
        $this->Data = $this->snapshot;
        $this->dirty();
    }

    function clean()
    {
        $this->dirty = false;
    }
    
    function dirty()
    {
        $this->dirty = true;
    }

    function isDirty()
    {
        return $this->dirty;
    }

    function getOwner()
    {
        return $this->owner;
    }

    function setOwner( \Core\Object $owner )
    {
        $this->owner = $owner;
    }

    public function offsetSet($offset, $value)
    {
        $this->dirty();
        return parent::offsetSet( $offset, $value );
    }

    public function offsetUnset($offset)
    {
        $this->dirty();
        parent::offsetUnset($offset);
    }
}