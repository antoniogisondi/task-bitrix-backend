<?php

class UserService
{
    private BitrixClient $client;

    public function __construct(BitrixClient $client)
    {
        $this->client = $client;
    }

    public function list(array $filter = []): array
    {
        $params = [];

        if (!empty($filter)) {
            $params['FILTER'] = $filter;
        }

        return $this->client->call('user.get', $params);
    }
}