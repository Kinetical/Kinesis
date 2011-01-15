<?php
namespace Core\Interfaces;

interface Parameterized
{
    function hasParameter( $key );
    function addParameter( $parameter );
    function removeParameter( $key );
    function getParameters();
    function setParameters( array $parameters );
    function getParameter( $key );
    function setParameter( $key, $value );
}