<?php
namespace DBAL\Data;

use \Core\Interfaces as I;
class Table extends \Core\Object implements I\Nameable
{
    private $_model;
    private $_columns;
    private $_rows;

    function __construct( Model $model )
    {
        $this->setModel( $model );
        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();

        $this->_columns = new \Core\Collection('\DBAL\Data\Table\Column');
        $this->_rows = new \Core\Collection('\DBAL\Data\Table\Row');
    }
    function getName()
    {
        return $this->getModel()->getName();
    }

    function setName( $name )
    {
        $this->getModel()->setName( $name );
    }

    function getModel()
    {
        return $this->_model;
    }

    function setModel( Model $model )
    {
        $this->_model = $model;
    }
}