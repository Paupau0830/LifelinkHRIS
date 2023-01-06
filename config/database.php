<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'hris.cotpfswxctlt.us-east-1.rds.amazonaws.com',
    'database' => 'hrisv2',
    'username' => 'admin',
    'password' => 'hrisv2_password',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => 'tbl_',
]);

$capsule->setAsGlobal();
