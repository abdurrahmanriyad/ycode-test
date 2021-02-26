<?php

declare(strict_types=1);


namespace App\Services\Team;

use App\Exceptions\FailedToCreateTableData;
use App\Exceptions\FailedToFetchTableData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class AirtableGridView implements DatabaseClient
{
    private string $token;

    protected string $endpoint;

    public function __construct()
    {
        $this->token = config('team.providers.airtable.api_key') ?? '';

        $this->endpoint = config('team.providers.airtable.table_endpoint') ?? '';
    }

    /**
     * Get all the team members from airtable api
     * @return Collection
     * @throws FailedToFetchTableData
     */
    public function getAll(): Collection
    {
        $response = Http::withToken($this->token)
            ->get($this->endpoint);

        if (!$response->successful()) {
            throw new FailedToFetchTableData;
        }

        return $this->buildCollectionOfRecords($response->json()['records']);
    }

    /**
     * Create a new team member to airtable api
     * @param array $data
     * @return array
     * @throws FailedToCreateTableData
     */
    public function create(array $data): array
    {
        $response = Http::withToken($this->token)
            ->post($this->endpoint, $data);

        if (!$response->successful()) {
            dd($response->json());
            throw new FailedToCreateTableData;
        }

        return $this->buildRecord($response->json());
    }

    /**
     * Prepares all the records with a new collection out of airtable response
     * @param array $records
     * @return Collection
     */
    private function buildCollectionOfRecords(array $records): Collection
    {
        $recordCollection = collect();

        foreach ($records as $record) {
            $recordCollection->push($this->buildRecord($record));
        }

        return $recordCollection;
    }

    /**
     * Prepare single record from airtable single record response
     * @param array $record
     * @return array
     */
    private function buildRecord(array $record): array
    {
        return [
            'name' => $record['fields']['Name'],
            'photo' => $record['fields']['Photo'][0] ?? null,
            'email' => $record['fields']['Email']
        ];
    }
}
