#!/usr/bin/env php

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once(dirname(__DIR__).'/src/config/boot.php');
require_once(dirname(__DIR__).'/src/base/core/autoload.php');
$console_config = require_once(dirname(__DIR__).'/config/console.php');

print ("start console command \n");
while (true) {
    
    foreach ($console_config['autoload'] as $val) {
        $config = require(dirname(__DIR__)."/../users/$val/config/config.php");
        $app = new \base\console\App();
        echo $app->start($config, $argv[1], $argv[2], $argv[3]);
    }
    sleep(2);
}
print ("end console command \n");
exit(0);