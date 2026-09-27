<?php

namespace App\Actions\Batch;

use App\Services\BatchService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchBatchesAction
{
    public function __construct(protected BatchService $batchService) {}

    public function execute(array $filters = []): Collection|LengthAwarePaginator
    {
        return $this->batchService->getAll($filters);
    }
}
