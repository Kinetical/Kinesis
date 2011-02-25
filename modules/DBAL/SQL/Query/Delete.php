<?php
namespace DBAL\SQL\Query;

class Delete extends Statement
{  
    function execute()
    {
        $platform = $this->getPlatform();
        
        return $platform->delete();
    }
}