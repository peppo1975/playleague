<?php
$data = file_get_contents($argv[1]);
$data = json_decode($data);

var_dump($data);
