<?php
namespace DBAL\SQL\Query;

class Add extends Statement
{
    function __construct( $attribute, \Kinesis\Task $parent )
    {
        $parent->Children['Alter']->addChild( $this );
        parent::__construct( array('Attribute' => $attribute ),
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
        
        return $platform->add().parent::execute();
    }
}