<?php
// config/bitrix.php
// Carica le variabili d'ambiente dal file .env (se non usa Composer/dotenv)

$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

define('BITRIX_WEBHOOK_URL',    $_ENV['BITRIX_WEBHOOK_URL']    ?? '');
define('BITRIX_DEFAULT_USER_ID', (int)($_ENV['BITRIX_DEFAULT_USER_ID'] ?? 11));
