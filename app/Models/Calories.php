<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calories extends Model
{
    const MEALS = [
        'snack' => [
            'name' => 'SNACK'
        ],
        'breakfast' => [
            'name' => 'BREAKFAST'
        ],
        'lunch' => [
            'name' => 'LUNCH'
        ],
        'dinner' => [
            'name' => 'DINNER'
        ]
    ];
}
