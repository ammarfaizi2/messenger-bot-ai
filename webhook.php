<?php
require __DIR__ . '/config.php';
require __DIR__ . '/vendor/autoload.php';

use System\ActionHandler;

(new ActionHandler($config))->run();