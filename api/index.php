<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once(dirname(__DIR__).'/config/boot.php');
require_once(dirname(__DIR__).'/config/api/boot.php');
require_once(dirname(__DIR__).'/src/base/core/autoload.php');
$config = array_merge(
    require(dirname(__DIR__).'/config/config.php'),
    require(dirname(__DIR__).'/config/api/config.php')
);

$app = new \base\core\App();
$app->start($config);