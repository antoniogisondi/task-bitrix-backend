<?php

require_once __DIR__ . '/../bootstrap.php';

requirePost();

$data = getJsonInput();

requireFields($data, [
    'task_id'
]);

$taskId = (int) $data['task_id'];

if ($taskId <= 0) {
    sendJson([
        'success' => false,
        'message' => 'ID task non valido.'
    ], 400);
}

$service = taskService();

$result = $service->get($taskId);

if (!$result['success']) {
    sendJson([
        'success' => false,
        'message' => $result['message'] ?? 'Errore durante il recupero del task.',
        'bitrix_response' => $result
    ], 400);
}

$bitrixResult = $result['data']['result'] ?? null;

if(empty($bitrixResult)){
      sendJson([
        'success' => false,
        'message' => 'Task non trovato oppure non accessibile con questo webhook.',
        'task_id' => $taskId,
        'bitrix_response' => $result['data']
    ], 404);
}

$task = $result['data']['result']['task'] ?? null;

sendJson([
    'success' => true,
    'message' => 'Task recuperato correttamente.',
    'task' => $task,
    'bitrix_response' => $result['data']
]);