<?php
namespace IO\Serial;

class Loader extends \IO\File\Loader
{
    function initialize()
    {
        if( !$this->caching() )
            parent::setCache(
                    new Cache($this));
        parent::initialize();
    }
}