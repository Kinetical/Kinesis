<?php
namespace DBAL\SQL\Query;

class Columns extends Statement
{
    function execute()
    {
        $platform = $this->getPlatform();
        
        return $platform->show().
               $platform->columns();
               //TODO: IN DATABASE
    }
}