<?php
require_once(__DIR__.'/../vendor/autoload.php');

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->register();

$loader->registerNamespace('SoampliApps', array(__DIR__.'/../src', __DIR__));
