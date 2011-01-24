<?php
$route = 'module/action/id';
$params['controller'] = 'mycontrol';

$against = 'article/something/10';

$keys = explode('/', $route );
$values = explode('/', $against );

$matches = array_combine( $keys, $values );
$matches = array_merge( $matches, $params );

var_Dump( $matches );