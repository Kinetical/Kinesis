<?php
//error_reporting( E_ERROR | E_RECOVERABLE_ERROR | E_PARSE | E_USER_ERROR | E_CORE_ERROR );
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
//error_reporting(0);
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime(true);
include('modules/AutoLoader.php');
AutoLoader::getInstance()->register();

include('modules/Core.php');

$webRequest = new MVC\WebRequest();

$webRequest->route();

echo $webRequest->getResponse();

$time_end = microtime(true);
$time = round($time_end - $time_start,2);

echo "<br/>";
echo $time.' seconds';
?>