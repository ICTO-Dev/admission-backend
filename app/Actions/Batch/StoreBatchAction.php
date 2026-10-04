<?php

namespace App\Actions\Batch;

use App\Models\Batch;
use App\Services\BatchService;

class StoreBatchAction
{
    public function __construct(protected BatchService $batchService) {}

    public function execute(array $data): Batch
    {
        return $this->batchService->create($data);
    }
}
