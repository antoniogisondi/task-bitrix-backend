<?php
// src/Bitrix/TaskService.php

namespace App\Bitrix;

class TaskService
{
    public function __construct(private BitrixClient $client) {}

    // ------------------------------------------------------------------
    // CRUD base
    // ------------------------------------------------------------------

    /** Crea un nuovo task. Restituisce l'array con i dati del task creato. */
    public function create(array $fields): array
    {
        return $this->client->call('tasks.task.add', ['fields' => $fields]);
    }

    /** Legge un task per ID. */
    public function get(int $taskId): array
    {
        return $this->client->call('tasks.task.get', ['taskId' => $taskId]);
    }

    /**
     * Lista task con filtri opzionali.
     *
     * Esempi di $filter: ['RESPONSIBLE_ID' => 11, 'STATUS' => 2]
     * Esempi di $select: ['ID', 'TITLE', 'STATUS', 'DEADLINE']
     */
    public function list(array $filter = [], array $select = [], int $start = 0): array
    {
        return $this->client->call('tasks.task.list', [
            'filter' => $filter,
            'select' => $select ?: ['ID', 'TITLE', 'STATUS', 'DEADLINE', 'RESPONSIBLE_ID'],
            'start'  => $start,
        ]);
    }

    /** Modifica i campi di un task esistente. */
    public function update(int $taskId, array $fields): array
    {
        return $this->client->call('tasks.task.update', [
            'taskId' => $taskId,
            'fields' => $fields,
        ]);
    }

    /** Elimina un task. */
    public function delete(int $taskId): array
    {
        return $this->client->call('tasks.task.delete', ['taskId' => $taskId]);
    }

    // ------------------------------------------------------------------
    // Cambio stato
    // ------------------------------------------------------------------

    public function start(int $taskId): array
    {
        return $this->client->call('tasks.task.start', ['taskId' => $taskId]);
    }

    public function pause(int $taskId): array
    {
        return $this->client->call('tasks.task.pause', ['taskId' => $taskId]);
    }

    public function complete(int $taskId): array
    {
        return $this->client->call('tasks.task.complete', ['taskId' => $taskId]);
    }

    public function renew(int $taskId): array
    {
        return $this->client->call('tasks.task.renew', ['taskId' => $taskId]);
    }

    // ------------------------------------------------------------------
    // Allegati
    // ------------------------------------------------------------------

    public function attachFile(int $taskId, int $fileId): array
    {
        return $this->client->call('tasks.task.files.attach', [
            'taskId' => $taskId,
            'fileId' => $fileId,
        ]);
    }

    // ------------------------------------------------------------------
    // Utilità
    // ------------------------------------------------------------------

    /** Restituisce i campi disponibili per i task. */
    public function getFields(): array
    {
        return $this->client->call('tasks.task.getFields');
    }
}
