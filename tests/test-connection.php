<?php
// tests/test-connection.php

require_once __DIR__ . '/../config/autoload.php';
require_once __DIR__ . '/../config/bitrix.php';

use App\Bitrix\BitrixClient;
use App\Bitrix\TaskService;

$client  = new BitrixClient(BITRIX_WEBHOOK_URL);
$service = new TaskService($client);

echo "🔍 Test connessione Bitrix24..." . PHP_EOL;
echo "URL: " . BITRIX_WEBHOOK_URL . PHP_EOL . PHP_EOL;

$result = $service->getFields();

if ($result['success']) {
    echo "✅ Connessione OK — permessi webhook attivi." . PHP_EOL;
    $fields = array_keys($result['data'] ?? []);
    echo "Campi disponibili: " . implode(', ', array_slice($fields, 0, 10)) . "..." . PHP_EOL;
} else {
    echo "❌ Connessione fallita." . PHP_EOL;
    echo "Errore: " . $result['error'] . PHP_EOL;
    echo "Dettaglio: " . $result['message'] . PHP_EOL;

    if ($result['error'] === 'insufficient_scope') {
        echo PHP_EOL . "👉 Soluzione: abilita 'Task management' nel pannello webhook di Bitrix24." . PHP_EOL;
    }
}
