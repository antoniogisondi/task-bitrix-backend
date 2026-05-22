<?php
class CompanyService
{
    private BitrixClient $client;

    public function __construct(BitrixClient $client)
    {
        $this->client = $client;
    }

     public function list(array $filter = []): array
    {
        return $this->client->call('crm.company.list', [
            'filter' => $filter,
            'select' => [
                'ID',
                'TITLE'
            ],
            'order' => [
                'TITLE' => 'ASC'
            ]
        ]);
    }
}