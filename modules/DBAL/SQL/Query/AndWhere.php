<?php
namespace DBAL\SQL\Query;

class AndWhere extends Where
{
    function execute()
    {
        $platform = $this->getPlatform();
        
        return $platform->bitwiseAnd().parent::execute();
    }
}