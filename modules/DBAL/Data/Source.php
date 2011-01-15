<?php
namespace DBAL\Data;

class Source extends Item
{
    private $_view;

    function __construct( array $data = null )
    {
            parent::__construct( $data );
            //parent::setDataType('DataItem');
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

    function __set( $name, $value )
    {
            if( $this->exists( $name )
                    && $name !== 'Data' )
            {
                $obj = $this[$name];
                if( $obj instanceof \Core\Object)
                    $obj->setData( $value );
            }
            /*else
            {
                    $dataType = $this->getDataType();
                    $item = new $dataType();
                    $item->setData( $value );
                    parent::__set( $name, $item );
            }*/

            parent::__set( $name, $value );
    }

    function Fill( View $view )
    {
            //if(!$view->Command->Query->Resource->Stream->isOpen() )
                    //$view->Command->Query->Resource->Stream->open();
                    //$hydrator = $view->Command->Resource->Hydrator;
//            $resource = $view->getCommand()->getQuery()->getResource();
//
//            if( $resource->getStream()->getMode() == \IO\Stream::WRITE )
//                    $resource->getLoader()->setSource( $this );

            $this->setData( $view->execute() );

                           // var_dump( $this->Data );
    }

    function getView()
    {
        if( is_null( $this->_view ))
            $this->_view = $this->getDefaultView();

        return $this->_view;
    }

    protected function getDefaultView()
    {
        return new View();
    }
}