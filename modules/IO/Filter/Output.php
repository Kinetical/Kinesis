<?php
namespace IO\Filter;

abstract class Output extends Filter
{
    function initialize()
    {
        parent::initialize();
        $this->state = self::OUTPUT;
    }
}