<?php
require_once __DIR__ . '/../bootstrap.php';

requirePost();

$data = getJsonInput();

$filter = $data['filter'] ?? [];

$filter['ACTIVE'] = true;

$service = userService();

$result = $service->list($filter);

if (!$result['success']) {
    sendJson([
        'success' => false,
        'message' => 'Errore recupero utenti: ' . $result['message'],
        'bitrix_response' => $result
    ], 400);
};

$users = $result['data']['result'] ?? [];

sendJson([
    'success' => true,
    'message' => 'Lista utenti recuperata correttamente.',
    'count' => count($users),
    'users' => $users,
    'bitrix_response' => $result['data']
]);


