<?php
namespace DBAL\XML\View;

class Entity extends \DBAL\XML\View
{
    function __construct( array $params = array(), Adapter $adapter = null )
    {
        if( !array_key_exists('path',$params))
            $params['path'] = 'site/entity.xml';
        if( !array_key_exists('xpath',$params))
            $params['xpath'] = '/core/entity';
        parent::__construct( $params, $adapter );
    }
    
    function prepare( $source = null )
    {
        parent::prepare( $source );

        if( $source instanceof \DBAL\Data\Source )
            if( $this->adapter->isRead() )
                $source->Map->recurse( new \DBAL\XML\Filter\Entity() );
        
        return $this->command;
    }
}