<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jukebox extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'queue_no',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function songQueue()
    {
        return $this->hasMany(UserSongQueue::class, 'user_id', 'user_id');
    }
}
