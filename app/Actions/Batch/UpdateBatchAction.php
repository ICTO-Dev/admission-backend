<?php

namespace App\Actions\Batch;

use App\Models\Batch;
use App\Services\BatchService;

class UpdateBatchAction
{
    public function __construct(protected BatchService $batchService) {}

    public function execute(Batch $batch, array $data): Batch
    {
        return $this->batchService->update($batch, $data);
    }
}
