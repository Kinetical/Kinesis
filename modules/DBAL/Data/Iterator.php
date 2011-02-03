<?php
namespace DBAL\Data;

class Iterator extends \IO\Stream\Iterator
{
    private $_platform;
    private $_limit;

    function __construct( \DBAL\Database $database, $input = null, $delegate = null )
    {
        $this->_platform = $database->getPlatform();

        if( is_null( $delegate ))
            $delegate = 'fetchAssoc';
        
        if( is_string( $delegate ))
            $delegate = new \Core\Delegate( $this->_platform, $delegate );

        parent::__construct( $delegate, $database->getConnection() );
        parent::setShared( true );
        if( !is_null( $input ))
            $this->setInput( $input );

    }

    function valid()
    {
        if( !is_int( $this->_limit ))
            return false;
        
        if( is_int( $this->_limit ) &&
            $this->position >= $this->_limit )
            return false;

        return parent::valid();
    }

    function setInput( $input )
    {
        $this->_limit = $this->_platform->rowCount( $input );

        parent::setInput( $input );
    }
}