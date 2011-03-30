<?php
namespace DBAL\SQL\Query;

class Drop extends Statement
{
    function __construct( $item, \Kinesis\Task $parent )
    {
        $container = $parent->Parameters['Container'];
        $container->addChild( $this );
        parent::__construct( array('Item' => $item), $container );
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