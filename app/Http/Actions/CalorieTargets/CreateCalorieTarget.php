<?php

namespace App\Http\Actions\CalorieTargets;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateCalorieTarget
{
    public function execute(int $user, int $target = 2500, $take_effect = null): int
    {
        if ($take_effect == null) {
            $take_effect = Carbon::today()->startOfDay();
        }

        return DB::table('calorie_targets')
            ->updateOrInsert(
                ['user_id' => $user, 'take_effect' => $take_effect],
                ['target' => $target, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
            );
    }
}
