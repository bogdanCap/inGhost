<?php

namespace App\Models;

use App\MessageBrodcast;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ChatSessionGroup extends Model
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(ChatSession::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
