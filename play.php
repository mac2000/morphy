<?php
use Morphy\Morphy;

require_once 'vendor/autoload.php';

$morphy = new Morphy();

print_r($morphy->base('Киеве'));
print_r($morphy->all('Киев'));
print_r($morphy->where('Киев'));