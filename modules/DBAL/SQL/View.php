<?php
namespace DBAL\SQL;

abstract class View extends \DBAL\Data\View
{
    function getDefaultQuery()
    {
        return new \DBAL\SQL\Query();
    }
}