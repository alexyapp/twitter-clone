<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    /**
     * Get the messages of the convo.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the users of the conversation.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeOrderByLatestMessage($query)
    {
        return $query
                ->join('messages', 'conversations.id', '=', 'messages.conversation_id')
                ->groupBy('conversations.id')
                ->orderByRaw('max(messages.created_at) desc');
    }
}
