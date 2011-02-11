<?php
namespace IO\Filter;

abstract class Output extends \IO\Filter
{
    function initialize()
    {
        parent::initialize();
        $this->state = self::OUTPUT;
    }
}