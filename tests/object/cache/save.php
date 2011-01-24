<?php

$object = new Core\Object();
$object->something = 'value';

//var_dump( $object );
$cache = new IO\Object\Cache();
$cache['myObject'] = $object;
