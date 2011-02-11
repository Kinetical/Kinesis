<?php
namespace DBAL\Data;

use \Util\Interfaces as I;

class Table extends \Core\Object implements I\Nameable
{
    private $_columns;
    private $_rows;

    function initialize()
    {
        parent::initialize();

        $this->_columns = new \Util\Collection\Dictionary(array(),'\DBAL\Data\Table\Column');
        $this->_rows = new \Util\Collection\Dictionary(array(),'\DBAL\Data\Table\Row');
    }

    function getColumns()
    {
        return $this->_columns;
    }

    function setColumns( array $columns )
    {
        $this->_columns->merge( $columns );
    }

    function getRows()
    {
        return $this->_columns;
    }

    function setRows( array $rows )
    {
        $this->_columns->merge( $rows );
    }

}