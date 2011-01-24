<?php

$config = new \DBAL\Configuration();

$user = $config->getUser();

var_dump( $user['name'] );