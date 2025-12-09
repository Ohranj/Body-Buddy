<?php

namespace App\Http\Actions\Calories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RetrieveCaloriesByDay
{

    public function execute($day = null): Collection
    {
        if (!$day) {
            $day = Carbon::today();
        }
        return DB::table('calories')->where('date', $day)->get();
    }
}
