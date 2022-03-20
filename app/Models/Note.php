<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'details',
        'completed_at'
    ];

    protected $casts = [
        'user_id' => 'integer'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

}
