<?php
namespace DBAL\SQL\Query;

class Change extends Statement
{
    function __construct( $name, $attribute, \Kinesis\Task $parent )
    {
        $parent->Children['Alter']->addChild( $this );
        
        $params = array('Attribute' => $attribute,
                        'Name' => $name );
        
        parent::__construct( $params,
                          $parent->Children['Alter'] );
    }
    
    function initialise()
    {
        $attribute = $this->Parameters['Attribute'];
        if( $attribute instanceof \DBAL\Data\Entity\Attribute )
        {
            $this->Children[] = new Attribute( $attribute, $this );
        }
    }
    
    function execute()
    {
        $platform = $this->getPlatform();
        
        return $platform->change( $this->Parameters['Name'] ).
               parent::execute(); 
    }
}