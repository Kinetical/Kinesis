<?php
namespace IO\Filter;

abstract class Input extends \IO\Filter
{
    function initialize()
    {
        parent::initialize();
        $this->state = self::INPUT;
    }
}