<?php

declare(strict_types=1);


namespace App\Services\Team;

use App\Exceptions\FailedToFetchTableData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Manager
{
    private DatabaseClient $databaseClient;

    public function __construct(DatabaseClient $databaseClient)
    {
        $this->databaseClient = $databaseClient;
    }

    public function getAll() : Collection
    {
        return $this->databaseClient->getAll();
    }

    public function create() : bool
    {
        return $this->databaseClient->create();
    }
}
