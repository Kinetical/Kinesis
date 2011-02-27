<?php
namespace IO\Filter;

abstract class Output extends \IO\Filter
{
    function initialise()
    {
        parent::initialise();
        $this->state = self::OUTPUT;
    }
}