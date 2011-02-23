<?php
namespace DBAL\SQL\Query;

class OrWhere extends Where
{
    function execute()
    {
        $platform = $this->getPlatform();
        
        return $platform->bitwiseOr().parent::execute();
    }
}