<?php
namespace DBAL\XML\Query;

class OrAttribute extends Attribute
{   
    function execute()
    {       
        return ' or '.parent::execute();
    }
}
