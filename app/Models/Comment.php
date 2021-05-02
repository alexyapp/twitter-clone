<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
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
     * Get the parent commentable model.
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Get the author of the comment.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the parent comment of this comment.
     */
    public function parentComment()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get all of the tweet's comments.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
