<?php
namespace Util\Arrays;
/**
 * ARRAY LIBRARY, MUST BE INCLUDED VIA LIBRARY DIRECTIVE IN CORE CONFIG
 */
function Convert( $mixed )
{
    if (empty($mixed))
        $array = array();
    elseif (is_object($mixed))
        $array = get_object_vars($mixed);
    elseif (!is_array($mixed))
        $array = array($mixed);
    
    return $array;
}

function is_array(&$array)
{
    return (bool)($array instanceof ArrayAccess || \is_array($array));
}

function names( array $nameables )
{
    $names = array();

    foreach( $nameables as $nameable )
        if( $nameable instanceof \Util\Interfaces\Nameable ||
            is_object( $nameable ) &&
            method_exists( 'getName', $nameable ))
            $names[ $nameable->getName() ] = $nameable;

    return $names;
}