<?php
namespace DBAL\Data;

class Source extends \Util\Collection\Persistent
{
    protected $view;
    protected $handler;
    public $Map;

    function initialise()
    {
        //parent::initialize();
        $this->handler = new \IO\Filter\Handler();
        $this->Map = &$this->handler->Map;
    }

//    function getMap()
//    {
//        return $this->handler->Map;
//    }
//
//    function setMap( $map )
//    {
//        $this->handler->setMap( $map );
//    }

    function hasMap()
    {
        return $this->handler->hasMap();
    }

    function getHandler()
    {
        return $this->handler;
    }

    function setHandler( IO\Filter\Handler $handler )
    {
        $this->handler = $handler;
    }

    function getViewClass()
    {
        return $this->view;
    }

    function setViewClass( $className )
    {
        if( is_class( $className ))
            $this->view = $className;
    }

    protected function getDefaultView()
    {
        return new $this->view();
    }
}