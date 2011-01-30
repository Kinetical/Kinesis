<?php
namespace DBAL\XML\View;

class Entity extends \DBAL\XML\View
{
    function prepare( \DBAL\Data\Source $dataSource = null )
    {
        parent::prepare();

        if( $this->adapter->isRead() )
        {
            $this->Filters->register( new \Core\Filter\Recursive( new \DBAL\Data\Filter\Entity() ) );
        }

        return $this->command;
    }
}