<?php
// api/create-task.php

require_once __DIR__ . '/../config/autoload.php';
require_once __DIR__ . '/../config/bitrix.php';
require_once __DIR__ . '/../config/app.php';

use App\Bitrix\BitrixClient;
use App\Bitrix\TaskService;

// --- Bootstrap ---
$client  = new BitrixClient(BITRIX_WEBHOOK_URL);
$service = new TaskService($client);

// --- Dati del task ---
// In futuro qui leggerai $_POST o json_decode(file_get_contents('php://input'))
$fields = [
    'TITLE'               => 'Incarico di prova da API',
    'DESCRIPTION'         => "Azienda: BISMATICA\n\nIncarico creato via API.",
    'RESPONSIBLE_ID'      => BITRIX_DEFAULT_USER_ID,
    'DEADLINE'            => '2026-06-30T18:00:00+02:00',
    'ALLOW_TIME_TRACKING' => 'Y',
    'ACCOMPLICES'         => [],
    'AUDITORS'            => [],
];

// --- Chiamata ---
$result = $service->create($fields);

// --- Output (CLI o risposta JSON per Ajax futuro) ---
if (php_sapi_name() === 'cli') {
    // Esecuzione da terminale
    echo ($result['success'] ? '✅ Task creato' : '❌ Errore: ' . $result['message']) . PHP_EOL;
    echo json_encode($result['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
} else {
    // Esecuzione via browser/Ajax — risponde con JSON
    header('Content-Type: application/json');
    echo json_encode($result);
}
