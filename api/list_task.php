<?php
require_once __DIR__ . '/../bootstrap.php';

requirePost();

$data = getJsonInput();

$filter = $data['filter'] ?? [];

$select = $data['select'] ?? [
    'ID',
    'TITLE',
    'DESCRIPTION',
    'STATUS',
    'RESPONSIBLE_ID',
    'DEADLINE',
    'CREATED_DATE',
    'CHANGED_DATE',
    'CLOSED_DATE'
];

$order = $data['order'] ?? [
    'ID' => 'DESC'
];

$service = taskService();

$result = $service->list($filter, $select, $order);

if(!$result['success']){
    sendJson([
        'success' => false,
        'message' => $result['message'] ?? 'Errore durante il recupero della lista task.',
        'bitrix_response' => $result
    ], 400);
}

$tasks = $result['data']['result']['tasks'] ?? [];

sendJson([
    'success' => true,
    'message' => 'Lista task recuperata correttamente.',
    'count' => count($tasks),
    'tasks' => $tasks,
    'bitrix_response' => $result['data']
]);

