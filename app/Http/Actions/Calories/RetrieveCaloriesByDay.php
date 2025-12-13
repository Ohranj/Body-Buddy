<?php

namespace App\Http\Actions\Calories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RetrieveCaloriesByDay
{

    public function execute($day = null): Collection
    {
        $start = $this->getStartDate($day);
        $end = $this->getEndDate($day);
        return DB::table('calories')->where('user_id', Auth::id())
            ->whereBetween('date', [$start, $end])->orderBy('date', 'asc')
            ->get();
    }

    /**
     * 
     */
    private function getStartDate($day): string
    {
        return $day->startOfDay()->toDateTimeString();
    }

    /**
     * 
     */
    private function getEndDate($day): string
    {
        return $day->endOfDay()->toDateTimeString();
    }
}
