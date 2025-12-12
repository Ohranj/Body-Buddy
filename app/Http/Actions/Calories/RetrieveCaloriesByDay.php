<?php

namespace App\Http\Actions\Calories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RetrieveCaloriesByDay
{

    public function execute($day = null): Collection
    {
        $start = $day->startOfDay()->toDateTimeString();
        $end = $day->endOfDay()->toDateTimeString();
        return DB::table('calories')->where('user_id', Auth::id())->whereBetween('date', [$start, $end])->get();
    }
}
