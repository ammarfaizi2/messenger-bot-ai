<?php
require __DIR__ . '/config.php';
require __DIR__ . '/vendor/autoload.php';

use System\ActionHandler;

defined('data') or define('data', __DIR__ . '/data');
is_dir(data) or mkdir(data);

(new ActionHandler($config))->run();
