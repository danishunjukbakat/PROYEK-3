<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;
    protected $fillable = [
    'category_id',
    'code',
    'title',
    'description',
    'start_at',
    'end_at',
    'location',
    'capacity',
    'status',
    ];

   protected function casts(): array
    {
    return [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];
    }

    public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}
}
