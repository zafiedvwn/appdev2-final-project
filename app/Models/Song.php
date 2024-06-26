<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $primaryKey = 'song_id';

    protected $fillable = [
        'song_title',
        'album',
        'artist',
        'year',
    ];

    public function userSongQueues()
    {
        return $this->hasMany(UserSongQueue::class, 'song_id', 'song_id');
    }
}
