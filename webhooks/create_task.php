<?php

$url = "https://bismatica.bitrix24.com/rest/11/9y2uu9ziflmrsok2/tasks.task.add.json";

$body = [
    "fields" => [
        "TITLE" => "Test creazione incarico da PHP",
        "DESCRIPTION" => "Questo è un incarico di prova creato tramite API REST Bitrix24 da codice PHP.",
        "RESPONSIBLE_ID" => 11,
        "DEADLINE" => "2026-05-30T18:00:00+02:00",
        "ALLOW_TIME_TRACKING" => "Y"
    ]
];

// Inizializzo cURL
$ch = curl_init($url);

// Configuro la richiesta POST
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Accept: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($body, JSON_UNESCAPED_UNICODE),
    CURLOPT_TIMEOUT => 30
]);

// Eseguo la chiamata
$response = curl_exec($ch);

// Gestione errore cURL
if ($response === false) {
    echo "Errore cURL: " . curl_error($ch) . PHP_EOL;
    curl_close($ch);
    exit;
}

// Recupero codice HTTP
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Chiudo cURL
curl_close($ch);

// Decodifico risposta JSON
$result = json_decode($response, true);

// Stampo risultato
echo "HTTP CODE: " . $httpCode . PHP_EOL;
echo "RISPOSTA BITRIX:" . PHP_EOL;

if (json_last_error() === JSON_ERROR_NONE) {
    print_r($result);
} else {
    echo $response . PHP_EOL;
}

?>