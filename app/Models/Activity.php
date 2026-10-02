<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];


    protected $fillable = [
        'category_id', 
        'code',
        'title',
        'description',
        'status',
        'start_at',
        'capacity',
        'activity_date',  // kolom lama, sementara
        'end_at',
        'location'
    ];

    protected $casts = [
        'activity_date' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}