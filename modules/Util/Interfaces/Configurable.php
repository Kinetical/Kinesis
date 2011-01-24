<?php
namespace Util\Interfaces;

interface Configurable
{
    function getConfiguration();
    function setConfiguration( \Core\Configuration $config );
}