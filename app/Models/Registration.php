<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = ['activity_id', 'user_email'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
