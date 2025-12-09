<?php

namespace App\Http\Actions\CalorieTargets;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RetrieveLatestCalorieTarget
{
    public function execute()
    {
        return DB::table('calorie_targets')->where('id', Auth::id())->orderBy('created_at', 'desc')->first();
    }
}
