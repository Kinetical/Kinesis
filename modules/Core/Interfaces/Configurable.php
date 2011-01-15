<?php
namespace Core\Interfaces;

interface Configurable
{
    function getConfiguration();
    function setConfiguration( \Core\Configuration $config );
}