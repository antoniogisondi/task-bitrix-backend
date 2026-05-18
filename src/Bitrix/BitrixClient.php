<?php

class BitrixClient
{
    private string $webhookBaseUrl;

    public function __construct(string $webhookBaseUrl)
    {
        $this->webhookBaseUrl = rtrim($webhookBaseUrl, '/') . '/';
    }

    public function call(string $method, array $params = []): array
    {
        $url = $this->webhookBaseUrl . $method . '.json';

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode($params, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);

            return [
                'success' => false,
                'message' => 'Errore cURL: ' . $error
            ];
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'message' => 'Risposta JSON non valida',
                'http_code' => $httpCode,
                'raw_response' => $response
            ];
        }

        if (isset($decoded['error'])) {
            return [
                'success' => false,
                'message' => $decoded['error_description'] ?? $decoded['error'],
                'http_code' => $httpCode,
                'bitrix_response' => $decoded
            ];
        }

        return [
            'success' => true,
            'http_code' => $httpCode,
            'data' => $decoded
        ];
    }
}