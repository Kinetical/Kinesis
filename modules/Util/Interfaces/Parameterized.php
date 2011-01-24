<?php
namespace Util\Interfaces;

interface Parameterized
{
    function getParameters();
    function setParameters( array $parameters );
}