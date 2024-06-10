<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    use HasFactory;

    protected $primaryKey = 'coin_id';

    protected $fillable = [
        'user_id',
        'amount',
    ];

    // protected $table = 'coins';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
