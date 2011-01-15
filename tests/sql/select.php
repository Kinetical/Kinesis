<?php

$query = new \DBAL\SQL\Query();
$builder = $query->build()
                 ->select('*')
                 ->from('table')
                 ->where('field','value')
                 ->where('field2',2);

echo (string)$query;