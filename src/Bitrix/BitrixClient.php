<?php
// src/Bitrix/BitrixClient.php

namespace App\Bitrix;

class BitrixClient
{
    private string $webhookBaseUrl;
    private int    $timeout;

    public function __construct(string $webhookBaseUrl, int $timeout = 15)
    {
        $this->webhookBaseUrl = rtrim($webhookBaseUrl, '/') . '/';
        $this->timeout        = $timeout;
    }

    public function call(string $method, array $payload = []): array
    {
        $url      = $this->webhookBaseUrl . $method . '.json';
        $jsonBody = \json_encode($payload, JSON_UNESCAPED_UNICODE);  // ← backslash

        $ch = \curl_init();                                           // ← backslash
        \curl_setopt_array($ch, [                                     // ← backslash
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $jsonBody,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT        => $this->timeout,
        ]);

        $raw       = \curl_exec($ch);                                 // ← backslash
        $httpCode  = \curl_getinfo($ch, CURLINFO_HTTP_CODE);         // ← backslash
        $curlError = \curl_error($ch);                                // ← backslash
        \curl_close($ch);                                             // ← backslash

        if ($curlError) {
            return $this->error('curl_error', $curlError, $httpCode);
        }

        $data = \json_decode($raw, true);                             // ← backslash
        if (\json_last_error() !== JSON_ERROR_NONE) {                 // ← backslash
            return $this->error('invalid_json', 'Risposta non JSON: ' . $raw, $httpCode);
        }

        if (isset($data['error'])) {
            return $this->error(
                $data['error'],
                $data['error_description'] ?? 'Errore sconosciuto',
                $httpCode
            );
        }

        return [
            'success'  => true,
            'httpCode' => $httpCode,
            'data'     => $data['result'] ?? $data,
        ];
    }

    private function error(string $code, string $message, int $httpCode): array
    {
        return [
            'success'  => false,
            'httpCode' => $httpCode,
            'error'    => $code,
            'message'  => $message,
            'data'     => null,
        ];
    }
}
