<?php
namespace DBAL\SQL\Query;

abstract class Statement extends \Kinesis\Task\Statement
{
    function getDatabase()
    {
        return $this->getQuery()->getDatabase();
    }
    
    function getPlatform()
    {
        return $this->getQuery()->getDatabase()->getPlatform();
    }
    
    function getContainer()
    {
        return $this->Parent->Parameters['Container'];
    }
}