<?php
namespace DBAL\SQL;

class Reader extends \DBAL\Stream\Reader
{
    function read()
    {
        return mysql_fetch_assoc( $this->getStream()->getResource() );
    }
}
