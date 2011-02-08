<?php
namespace IO\Filter;

abstract class Input extends Filter
{
    function initialize()
    {
        parent::initialize();
        $this->state = self::INPUT;
    }
}