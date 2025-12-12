<?php

namespace App\Services;

use App\Models\Calories;
use Illuminate\Support\Collection;

class CaloriesService
{
    public $consumed = 0;
    public $percentConsumed = 0;
    public $remaining = 0;
    public $mealTallies = [];

    public function __construct(private Collection $calorieEntries, private int $target) {}

    /**
     * 
     */
    public function tallyPerMeal(): CaloriesService
    {
        $types = [];
        foreach (Calories::MEALS as $meal) {
            $types[$meal['name']] = [
                'amount' => 0,
                'percent' => 0
            ];
        }

        $this->sumCaloriesConsumed();

        $this->mealTallies = $this->calorieEntries->reduce(function ($acc, $cur) {
            $acc[$cur->meal]['amount'] += $cur->amount;
            return $acc;
        }, $types);

        foreach ($this->mealTallies as $key => $meal) {
            if ($meal['amount'] == 0) {
                continue;
            }
            $this->mealTallies[$key]['percent'] = round(($meal['amount'] / $this->consumed) * 100, 1);
        }

        return $this;
    }

    /**
     * 
     */
    public function sumCaloriesConsumed(): CaloriesService
    {
        $this->consumed = $this->calorieEntries->reduce(fn($acc, $cur) => $acc += $cur->amount, 0);
        return $this;
    }

    /**
     * 
     */
    public function calculateCaloriesConsumedPercent(int $consumed = 0): CaloriesService
    {
        if ($this->target == 0) {
            return $this;
        }

        if ($consumed == 0) {
            $consumed = $this->consumed;
        }

        $this->percentConsumed = round(($consumed / $this->target) * 100, 1);
        return $this;
    }

    /**
     * 
     */
    public function sumRemainingCalories(int $consumed = 0)
    {
        if ($this->target == 0) {
            return $this;
        }

        if ($consumed == 0) {
            $consumed = $this->consumed;
        }

        $this->remaining = $this->target - $consumed;
        return $this;
    }
}
