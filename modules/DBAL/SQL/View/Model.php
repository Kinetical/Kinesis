<?php
namespace DBAL\SQL\View;

class Model extends \DBAL\SQL\View
{
    function getDefaultSelect()
    {
        $command = $this->getDefaultQuery();

        $command->build()
                ->models();

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