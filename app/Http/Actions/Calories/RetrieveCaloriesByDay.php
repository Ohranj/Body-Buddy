<?php

namespace App\Http\Actions\Calories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RetrieveCaloriesByDay
{

    public function execute(int $user, $day = null): Collection
    {
        $start = $this->getStartDate(day: $day);
        $end = $this->getEndDate(day: $day);
        return DB::table('calories')->where('user_id', $user)
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
