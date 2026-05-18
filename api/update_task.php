<?php

require_once __DIR__ . '/../bootstrap.php';

requirePost();

$data = getJsonInput();

requireFields($data, [
    'taskId'
]);

$taskId = $data['taskId'];

if ($taskId <= 0) {
    sendJson([
        'success' => false,
        'message' => 'ID task non valido.'
    ], 400);
}

// Rimuovo task_id dai dati da aggiornare
unset($data['taskId']);

$service = taskService();

$result = $service->update($taskId, $data);

if (!$result['success']) {
    sendJson([
        'success' => false,
        'message' => $result['message'] ?? 'Errore durante la modifica del task.',
        'bitrix_response' => $result
    ], 400);
}

sendJson([
    'success' => true,
    'message' => 'Task aggiornato correttamente.',
    'taskId' => $taskId,
    'bitrix_response' => $result['data']
]);