<?php
namespace IO\Filter;

abstract class Input extends \IO\Filter
{
    function initialise()
    {
        parent::initialise();
        $this->state = self::INPUT;
    }
}