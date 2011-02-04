<?php
namespace DBAL\SQL\View;

class Table extends \DBAL\SQL\View
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
}