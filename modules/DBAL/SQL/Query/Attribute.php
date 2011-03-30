<?php
namespace DBAL\SQL\Query;

class Attribute extends Statement
{
    function __construct( $attribute, \Kinesis\Task $parent )
    {
        $parent->addChild( $this );
        $this->setComponent( $parent->Parent->getComponent() );
        parent::__construct( array('Attribute' => $attribute ),
                          $parent );
    }
    
    function initialise()
    {
        $attribute = $this->Parameters['Attribute'];
        
        if( $attribute instanceof \DBAL\Data\Entity\Attribute )
        {
            $this->Parameters['Name']   = $attribute->getName();
            $this->Parameters['Type']   = (string)$attribute->getTypeName();
            $this->Parameters['Length'] = $attribute->Length;
            $this->Parameters['Default']= $attribute->Default;
            $this->Parameters['Flags']  = $attribute->Flags;
        }
    }
    
    function execute()
    {
        extract( $this->Parameters );
        
        $platform = $this->getPlatform();
        
        return $platform->column( $Name, $Type, $Length, $Default, $Flags );
    }
}