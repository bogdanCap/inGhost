<?php

namespace App;

use App\Models\ChatSession;
use Illuminate\Database\Eloquent\Model;

class MessageBrodcast extends Model
{

    protected $fillable = ['message'];

    /**
     * A message belong to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(ChatSession::class);
    }

    /**
     * @return string|null
     */
    public function getParentUserId()
    {
        return $this->parent_user_id;
    }
}
