<?php

use BetterAltoRouter\BetterAltoRouter;

require dirname(__DIR__).'/vendor/autoload.php';

$router = new BetterAltoRouter([
    'files_path' => __DIR__,
    'layout_file' => '/abc.php'
]);

$router
    ->get('/', 'test')
    ->run();