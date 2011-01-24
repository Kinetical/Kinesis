<?php
namespace Util\Arrays;

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