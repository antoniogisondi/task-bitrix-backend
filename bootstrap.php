<<?php

header('Content-Type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/config/bitrix.php';

$bitrixClientPath = __DIR__ . '/src/Bitrix/BitrixClient.php';
$taskServicePath = __DIR__ . '/src/Bitrix/TaskService.php';

if (!file_exists($bitrixClientPath)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'File BitrixClient.php non trovato',
        'path' => $bitrixClientPath
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if (!file_exists($taskServicePath)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'File TaskService.php non trovato',
        'path' => $taskServicePath
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

require_once $bitrixClientPath;
require_once $taskServicePath;

if (!class_exists('BitrixClient')) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'La classe BitrixClient non è stata caricata. Controlla il nome della classe o eventuali namespace.',
        'path' => $bitrixClientPath
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if (!class_exists('TaskService')) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'La classe TaskService non è stata caricata. Controlla il nome della classe o eventuali namespace.',
        'path' => $taskServicePath
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

function getJsonInput(): array
{
    $rawBody = file_get_contents('php://input');

    if ($rawBody === '' || $rawBody === false) {
        return [];
    }

    $data = json_decode($rawBody, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        sendJson([
            'success' => false,
            'message' => 'JSON non valido.',
            'error' => json_last_error_msg()
        ], 400);
    }

    return $data ?? [];
}

function sendJson(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);

    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    exit;
}

function requirePost(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendJson([
            'success' => false,
            'message' => 'Metodo non consentito. Usa POST.'
        ], 405);
    }
}

function requireFields(array $data, array $fields): void
{
    foreach ($fields as $field) {
        if (!isset($data[$field]) || $data[$field] === '') {
            sendJson([
                'success' => false,
                'message' => "Campo obbligatorio mancante: {$field}"
            ], 400);
        }
    }
}

function taskService(): TaskService
{
    $client = new BitrixClient(BITRIX_WEBHOOK_URL);

    return new TaskService($client);
}