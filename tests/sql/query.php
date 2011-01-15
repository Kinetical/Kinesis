<?php

$driver = new \DBAL\Driver\MySQL();

$connection = new \DBAL\Connection( $driver );

$query = new \DBAL\SQL\Query();

$query->Text = "SELECT * FROM table";

$results = $query->execute();
echo $query->Text;