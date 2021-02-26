<?php

declare(strict_types=1);


namespace App\Services\Team;

use App\Exceptions\FailedToFetchTableData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class AirtableGridView implements DatabaseClient
{
    private string $token;

    protected string $baseUrl = "https://api.airtable.com/v0";

    protected array $endpoints = [];

    public function __construct()
    {
        $this->token = config('team.providers.airtable.api_key') ?? '';

        $this->initializeEndpoints();
    }

    public function getAll(): Collection
    {
        $response = Http::withToken($this->token)
            ->get($this->endpoints['getAll']);

        if (!$response->successful()) {
            throw new FailedToFetchTableData;
        }

        return  $this->buildCollectionOfRecords($response->json()['records']);
    }

    public function create(): bool
    {
        // TODO: Implement create() method.
    }

    private function buildCollectionOfRecords(array $records) : Collection
    {
        $recordCollection = collect();

        foreach ($records as $record) {
            $recordCollection->push([
                'name' => $record['fields']['Name'],
                'photo' => $record['fields']['Photo'][0],
                'email' => $record['fields']['Email']
            ]);
        }

        return  $recordCollection;
    }

    private function initializeEndpoints()
    {
        $this->endpoints = [
            'getAll' => $this->baseUrl . '/appeB7iJeBq92AoHD/Grid%20view?view=Grid%20view',
            'create' => 'asdfasdf'
        ];
    }
}
