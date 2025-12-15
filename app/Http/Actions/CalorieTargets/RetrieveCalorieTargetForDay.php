<?php

namespace App\Http\Actions\CalorieTargets;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RetrieveCalorieTargetForDay
{
    public function execute(int $user, $day)
    {
        return DB::table('calorie_targets')->where([
            ['user_id', $user],
            ['take_effect', '<=', $day]
        ])->orderBy('take_effect', 'desc')->orderBy('created_id', 'desc')->first();
    }
}
