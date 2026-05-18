<?php

require_once __DIR__ . '/../bootstrap.php';

requirePost();

$data = getJsonInput();

requireFields($data, [
    'title',
    'description',
    'responsible_id',
    'deadline'
]);

$service = taskService();

$result = $service->create($data);

if (!$result['success']) {
    sendJson([
        'success' => false,
        'message' => $result['message'] ?? 'Errore durante la creazione del task.',
        'bitrix_response' => $result
    ], 400);
}

$taskId = $result['data']['result']['task']['id'] ?? null;

sendJson([
    'success' => true,
    'message' => 'Task creato correttamente.',
    'task_id' => $taskId,
    'bitrix_response' => $result['data']
]);


