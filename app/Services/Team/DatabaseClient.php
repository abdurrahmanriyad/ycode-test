<?php

declare(strict_types=1);

namespace App\Services\Team;

use Illuminate\Support\Collection;

interface DatabaseClient
{
    public function getAll(): Collection;

    public function create(): bool;
}
