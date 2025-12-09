<?php

namespace App\Http\Actions\CalorieTargets;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateCalorieTarget
{
    public function execute($target = 2500): int
    {
        $dateNow = Carbon::now();
        return DB::table('calorie_targets')->insertGetId(['user_id' => Auth::id(), 'target' => $target, 'created_at' => $dateNow, 'updated_at' => $dateNow]);
    }
}
