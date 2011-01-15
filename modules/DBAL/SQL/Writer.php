<?php
namespace DBAL\SQL;

class Writer extends \DBAL\Stream\Writer
{
    function write(  $source  )
    {
        //TODO: step 2; ??
        //TODO: step 3: profit!
        return mysql_query( $sql );
    }
}
