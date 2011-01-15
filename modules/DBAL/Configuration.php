<?php
namespace DBAL;

class Configuration extends \Core\Configuration
{

    function initialize()
    {
        parent::initialize();

        $this->merge( $this['database'] );
    }
    function getLogger()
    {
        return $this['database/logger'];
    }

    function getUser()
    {
        return $this['database/user'];
    }
}