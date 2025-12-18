<?php

namespace App\Http\Actions\CalorieTargets;

use Illuminate\Support\Facades\DB;

class DeleteCalorieTargetById
{
    /**
     * 
     */
    public function execute(int $id, int $userId): int
    {
        return DB::table('calorie_targets')->where([['id', $id], ['user_id', $userId]])->delete();
    }
}
