<?php
namespace DBAL\XML\Query;

class AndAttribute extends Attribute
{   
    function execute()
    {       
        return ' and '.parent::execute();
    }
}
