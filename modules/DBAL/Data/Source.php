<?php
namespace DBAL\Data;

class Source extends \Util\Collection\Persistent
{
    protected $view;
    protected $handler;

    function initialise()
    {
        //parent::initialize();
        $this->handler = new \IO\Filter\Handler();
    }

    function getMap()
    {
        return $this->handler->getMap();
    }

    function setMap( $map )
    {
        $this->handler->setMap( $map );
    }

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