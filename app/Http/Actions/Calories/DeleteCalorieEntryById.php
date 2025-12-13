<?php

namespace App\Http\Actions\Calories;

use Illuminate\Support\Facades\DB;

class DeleteCalorieEntryById
{

    /**
     * 
     */
    public function execute(int $id, int $userId): int
    {
        return DB::table('calories')->where([['id', $id], ['user_id', $userId]])->delete();
    }
}
