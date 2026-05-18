<?php

class TaskService
{
    public function __construct(private BitrixClient $client) {}

    // ------------------------------------------------------------------
    // CRUD base
    // ------------------------------------------------------------------

   
    public function create(array $data): array
    {
        $fields = [];

        // Campi obbligatori
        $fields['TITLE'] = $data['title'];
        $fields['DESCRIPTION'] = ($data)['description'];
        $fields['RESPONSIBLE_ID'] = (int) $data['responsible_id'];
        $fields['DEADLINE'] = $data['deadline'];

        // Gestione tempo
        $fields['ALLOW_TIME_TRACKING'] = !empty($data['allow_time_tracking']) ? 'Y' : 'N';

        // Partecipanti
        if (!empty($data['participants']) && is_array($data['participants'])) {
            $fields['ACCOMPLICES'] = array_map('intval', $data['participants']);
        }

        // Osservatori
        if (!empty($data['auditors']) && is_array($data['auditors'])) {
            $fields['AUDITORS'] = array_map('intval', $data['auditors']);
        }

        // Collegamento azienda CRM Bitrix, se hai l'ID azienda
        if (!empty($data['company_id'])) {
            $fields['UF_CRM_TASK'] = ['CO_' . (int) $data['company_id']];
        }

        // Campi extra dinamici già nel formato Bitrix
        if (!empty($data['extra_fields']) && is_array($data['extra_fields'])) {
            foreach ($data['extra_fields'] as $key => $value) {
                $fields[$key] = $value;
            }
        }

         return $this->client->call('tasks.task.add', [
            'fields' => $fields
        ]);
    }


    /** Legge un task per ID. */
    public function get(int $taskId): array
    {
        return $this->client->call('tasks.task.get', ['taskId' => $taskId]);
    }


    public function list(array $filter = [], array $select = [], array $order = []): array
    {
        $params = [];

        if (!empty($filter)) {
            $params['filter'] = $filter;
        }

        if (!empty($select)) {
            $params['select'] = $select;
        }

        if (!empty($order)) {
            $params['order'] = $order;
        }

       return $this->client->call('tasks.task.list', $params);
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
