<?php
namespace DBAL\XML\View;

class Entity extends \DBAL\XML\View
{
    function prepare( \DBAL\Data\Source $dataSource = null )
    {
        parent::prepare();

        if( $this->adapter->isRead() )
        {
            new \DBAL\Data\Filter\Entity( $this->command );
        }

        return $this->command;
    }
}