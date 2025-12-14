<?php

namespace App\Http\Actions\CalorieTargets;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RetrieveCalorieTargetForDay
{
    public function execute($day)
    {
        return DB::table('calorie_targets')->where([
            ['user_id', Auth::id()],
            ['created_at', '<=', $day]
        ])->orderBy('created_at', 'desc')->orderBy('id', 'desc')->first();
    }
}
