<?php

namespace App\Http\Actions\Logs;

class InsertLog
{
    public function execute($model, string $activity, array $data = []): void
    {
        $model->logs()->create([
            'activity' => $activity,
            'meta_data' => json_encode($data),
        ]);
    }
}
