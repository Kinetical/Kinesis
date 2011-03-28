<?php
namespace DBAL\SQL\Query;

class Drop extends Statement
{
    function __construct( $item, \Kinesis\Task $parent )
    {
        parent::__construct( array('Item' => $item), $parent );
    }
    
    function initialise()
    {
        $item = $this->Parameters['Item'];
        if( $item instanceof \DBAL\Data\Entity )
        {
            $this->Parameters['Table'] = $item->getName();
        }
        elseif( $item instanceof \DBAL\Data\Entity\Attribute )
        {
            $this->Parameters['Column'] = $item->getName();
            $this->Parent->Children['Alter']->addChild( $this );
            unset( $this->Parent->Children['Drop']);
            $this->Parent = $this->Parent->Children['Alter'];
        }
    }
    
    function execute()
    {
        extract( $this->Parameters );
        
        $platform = $this->getPlatform();
        
        $query = $platform->drop();
        if( isset( $Table ))
            $query .= $platform->table( $Table );
        elseif( isset( $Column ))
            $query .= $platform->identifier( $Column );
        
        return $query;
    }
}