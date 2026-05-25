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
        $fields['DESCRIPTION'] = $data['description'];
        $fields['RESPONSIBLE_ID'] = (int) $data['responsible_id'];
        $fields['DEADLINE'] = $data['deadline'];
        $fields['STATUS'] = !empty($data['status']) ? (int) $data['status'] : 2;

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
        return $this->client->call('tasks.task.get', [
            'taskId' => $taskId,
            'select' => [
            '*',
            'UF_CRM_TASK',
            'UF_TASK_WEBDAV_FILES',
            'UF_MAIL_MESSAGE',
            'UF_AUTO_357479978862',
            'UF_AUTO_955961840475',
            'UF_AUTO_853416300964',
            'UF_AUTO_587188650894',
            'UF_AUTO_804421658935',
            'UF_AUTO_293519119469',
            'UF_AUTO_526872679459',
            'UF_AUTO_774498742199',
            'UF_AUTO_768767389181',
            'UF_AUTO_247234252507',
            'UF_AUTO_511562948962'
            ]
        ]);
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


    public function update(int $taskId, array $data): array
    {
        $fields = [];

         if (isset($data['title'])) {
        $fields['TITLE'] = $data['title'];
        }

        if (isset($data['description']) || isset($data['company']) || isset($data['signature'])) {
            $fields['DESCRIPTION'] = $this->$data;
        }

        if (isset($data['responsible_id'])) {
            $fields['RESPONSIBLE_ID'] = (int) $data['responsible_id'];
        }

        if (isset($data['deadline'])) {
            $fields['DEADLINE'] = $data['deadline'];
        }

        if (isset($data['status']) && $data['status'] !== '') {
            $fields['STATUS'] = (int) $data['status'];
        }

        if (isset($data['allow_time_tracking'])) {
            $fields['ALLOW_TIME_TRACKING'] = !empty($data['allow_time_tracking']) ? 'Y' : 'N';
        }

        if (isset($data['participants']) && is_array($data['participants'])) {
            $fields['ACCOMPLICES'] = array_map('intval', $data['participants']);
        }

        if (isset($data['auditors']) && is_array($data['auditors'])) {
            $fields['AUDITORS'] = array_map('intval', $data['auditors']);
        }

        if (!empty($data['company_id'])) {
            $fields['UF_CRM_TASK'] = ['CO_' . (int) $data['company_id']];
        }

        if (!empty($data['extra_fields']) && is_array($data['extra_fields'])) {
            foreach ($data['extra_fields'] as $key => $value) {
                $fields[$key] = $value;
            }
        }

        if (empty($fields)) {
            return [
                'success' => false,
                'message' => 'Nessun campo da aggiornare.'
            ];
        }

        return $this->client->call('tasks.task.update', [
        'taskId' => $taskId,
        'fields' => $fields
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

    public function action(int $taskId, string $action): array
    {
        $allowedActions = [
            'start' => 'tasks.task.start',
            'pause' => 'tasks.task.pause',
            'complete' => 'tasks.task.complete',
            'renew' => 'tasks.task.renew',
            'defer' => 'tasks.task.defer',
            'approve' => 'tasks.task.approve',
            'disapprove' => 'tasks.task.disapprove',
            'reject' => 'tasks.task.reject',
        ];

        if (!array_key_exists($action, $allowedActions)) {
            return [
                'success' => false,
                'message' => 'Azione task non valida.'
            ];
        }

        return $this->client->call($allowedActions[$action], [
            'taskId' => $taskId
        ]);
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
