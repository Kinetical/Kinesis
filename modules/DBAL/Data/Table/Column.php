<?php
namespace DBAL\Data\Table;

class Column extends \Core\Object
{
    private $_name;
    private $_attribute;

    function getName()
    {
        return $this->_name;
    }

    function setName( $name )
    {
        $this->_name = $name;
    }

    function getAttribute()
    {
        return $this->_attribute;
    }

    function setAttribute( \ORM\Entity\EntityAttribute $attribute )
    {
        $this->_attribute = $attribute;
    }
}