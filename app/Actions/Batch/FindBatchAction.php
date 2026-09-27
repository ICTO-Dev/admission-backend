<?php

namespace App\Actions\Batch;

use App\Models\Batch;
use App\Services\BatchService;

class FindBatchAction
{
    public function __construct(protected BatchService $batchService) {}

    public function execute(int|string $id): ?Batch
    {
        return $this->batchService->findById($id);
    }
}
