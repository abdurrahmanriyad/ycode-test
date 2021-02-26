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

    public function getAll(): Collection
    {
        $response = Http::withToken($this->token)
            ->get($this->endpoint);

        if (!$response->successful()) {
            throw new FailedToFetchTableData;
        }

        return $this->buildCollectionOfRecords($response->json()['records']);
    }

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

    private function buildCollectionOfRecords(array $records): Collection
    {
        $recordCollection = collect();

        foreach ($records as $record) {
            $recordCollection->push($this->buildRecord($record));
        }

        return $recordCollection;
    }

    private function buildRecord(array $record): array
    {
        return [
            'name' => $record['fields']['Name'],
            'photo' => $record['fields']['Photo'][0] ?? null,
            'email' => $record['fields']['Email']
        ];
    }
}
