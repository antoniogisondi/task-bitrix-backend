<?php

require_once __DIR__ . '/../bootstrap.php';

requirePost();

$data = getJsonInput();

$taskId = (int) ($data['task_id'] ?? $data['taskId'] ?? 0);
$action = $data['action'] ?? '';

if ($taskId <= 0) {
    sendJson([
        'success' => false,
        'message' => 'ID task non valido.'
    ], 400);
}

if ($action === '') {
    sendJson([
        'success' => false,
        'message' => 'Azione task non indicata.'
    ], 400);
}

$service = taskService();

$result = $service->action($taskId, $action);

if (!$result['success']) {
    sendJson([
        'success' => false,
        'message' => $result['message'] ?? 'Errore durante l’esecuzione dell’azione sul task.',
        'bitrix_response' => $result
    ], 400);
}

sendJson([
    'success' => true,
    'message' => 'Azione eseguita correttamente.',
    'task_id' => $taskId,
    'action' => $action,
    'bitrix_response' => $result['data']
]);