<?php
$query = new \DBAL\SQL\Query();
$builder = $query->build()
                 ->insert('table')
                 ->set('field','value')
                 ->set('field2', 2);

echo (string)$query;