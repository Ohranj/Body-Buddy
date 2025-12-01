<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Log extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'activity',
        'meta_data',
    ];

    /**
     * Relations
     */
    public function userable(): MorphTo
    {
        return $this->morphTo();
    }
}
