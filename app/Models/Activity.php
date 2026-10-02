<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'code',
        'title',
        'description',
        'status',
        'start_at',
        'capacity',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}