<?php
require_once __DIR__ . '/../bootstrap.php';

requirePost();

$data = getJsonInput();

$taskId = (int) ($data['taskId'] ?? $data['task_id'] ?? 0);

if($taskId <= 0){
    sendJson([
        'success' => false,
        'message' => 'ID task non valido.'
    ], 400);
}

$service = taskService();

$result = $service->delete($taskId);

if (!$result['success']) {
    sendJson([
        'success' => false,
        'message' => $result['message'] ?? 'Errore durante l’eliminazione del task.',
        'bitrix_response' => $result
    ], 400);
}

sendJson([
    'success' => true,
    'message' => 'Task eliminato correttamente.',
    'task_id' => $taskId,
    'bitrix_response' => $result['data']
]);


