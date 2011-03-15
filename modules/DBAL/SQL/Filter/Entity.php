<?php
namespace DBAL\SQL\Filter;

class Entity extends \IO\Filter
{
    function execute( array $params = array() )
    {
        var_dump( $params );
    }
}