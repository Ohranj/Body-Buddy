<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;

class CaloriesService
{
    public $consumed = 0;
    public $percentConsumed = 0;
    public $remaining = 0;

    public function __construct(private Collection $calorieEntries, private int $target) {}

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
