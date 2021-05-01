<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
    ];

    /**
     * Get the author of the tweet.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
