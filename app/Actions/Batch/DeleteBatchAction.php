<?php

namespace App\Actions\Batch;

use App\Models\Batch;
use App\Services\BatchService;

class DeleteBatchAction
{
    public function __construct(protected BatchService $batchService) {}

    public function execute(Batch $batch): bool
    {
        return $this->batchService->delete($batch);
    }
}
