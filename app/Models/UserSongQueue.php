<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserSongQueue extends Model
{
    use HasFactory;

    protected $primaryKey = 'queue_id';

    protected $fillable = [
        'user_id',
        'song_id',
        'status',
    ];

    protected $table = 'user_song_queue';

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relationship with Song
    public function song()
    {
        return $this->belongsTo(Song::class, 'song_id', 'song_id');
    }
}
