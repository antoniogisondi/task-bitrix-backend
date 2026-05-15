<?php
// config/autoload.php

spl_autoload_register(function (string $class): void {
    // App\Bitrix\TaskService → src/Bitrix/TaskService.php
    $base = __DIR__ . '/../src/';
    $file = $base . str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
