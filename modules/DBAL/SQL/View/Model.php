<?php
namespace DBAL\SQL\View;

class Entity extends \DBAL\SQL\View
{
    function getDefaultSelect()
    {
        $command = $this->getDefaultQuery();

        $command->build()
                ->tables();

        return $command;
    }

    function getDefaultInsert()
    {

    }

    function getDefaultUpdate()
    {

    }

    function getDefaultDelete()
    {

    }

    function prepare()
    {
        if( $this->adapter->isRead() )
        {
            $this->command->Filters->register( new \DBAL\Data\Filter\Table() );
        }
        return parent::prepare();
    }
}