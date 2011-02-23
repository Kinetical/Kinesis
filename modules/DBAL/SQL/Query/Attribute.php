<?php
namespace DBAL\SQL\Query;

class Attribute extends Statement
{
    function __construct( $attribute, \Kinesis\Task $parent )
    {
        $parent->Children['Create']->addChild( $this );
        parent::__construct( array('Attribute' => $attribute ),
                          $parent->Children['Create'] );
    }
    
    function initialise()
    {
        $attribute = $this->Parameters['Attribute'];
        
        if( $attribute instanceof \DBAL\Entity\Attribute )
        {
            $this->Parameters['Name']   = $attribute->getName();
            $this->Parameters['Type']   = (string)$attribute->DataType;
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