<?php

require_once __DIR__ . '/../bootstrap.php';

requirePost();

$data = getJsonInput();

$filter = $data['filter'] ?? [];

$service = companyService();

$result = $service->list($filter);

if (!$result['success']) {
    sendJson([
        'success' => false,
        'message' => $result['message'] ?? 'Errore durante il recupero delle aziende.',
        'bitrix_response' => $result
    ], 400);
}

$companies = $result['data']['result'] ?? [];

sendJson([
    'success' => true,
    'message' => 'Lista aziende recuperata correttamente.',
    'count' => count($companies),
    'companies' => $companies,
    'bitrix_response' => $result['data']
]);

