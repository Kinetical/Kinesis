<?php
namespace DBAL\Data;

class Source extends Item
{
    private $_view;
    private $_viewClass;

    function __construct( array $data = null )
    {
            parent::__construct( $data );
    }

    function setDataType( $type )
    {
        if( class_exists( $type ))
        {
            $class = new \ReflectionClass( $type );
            if( $class->isSubclassOf('\Core\Object'))
                    parent::setDataType( $type );
            else
                    throw new \Core\Exception('Invalid data type');
        }
    }

    function execute( View $view )
    {
        $results = $view->execute();

        $adapter = $view->getAdapter();

        if( $adapter->isSelectCommand() )
            $this->setData( $results );
        
        return $results;
    }

    function __invoke( View $view )
    {
        return $this->execute( $view );
    }

    function getView()
    {
        if( is_null( $this->_view ))
            $this->_view = $this->getDefaultView();

        return $this->_view;
    }

    function getViewClass()
    {
        return $this->_viewClass;
    }

    function setViewClass( $className )
    {
        if( is_class( $className ))
            $this->_viewClass = $className;
    }

    protected function getDefaultView()
    {
        return new $this->_viewClass();
    }
}