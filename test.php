<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

include('modules/AutoLoader.php');

AutoLoader::getInstance()->register();

include('modules/Core.php');

$core = Core::getInstance();

$testName = $_GET['test'];

include('tests/'.$testName.'.php');
