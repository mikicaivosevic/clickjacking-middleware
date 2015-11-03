<?php
// Enable Composer autoloader
$autoloader = require dirname(__DIR__) . '/vendor/autoload.php';
$autoloader->addPsr4('Clickjacking\\Middleware\\Test\\', __DIR__);