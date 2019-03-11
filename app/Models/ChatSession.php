<?php

namespace App\Models;

use App\MessageBrodcast;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{

    protected $fillable = ['name'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(MessageBrodcast::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessionGroup()
    {
        return $this->hasMany(ChatSessionGroup::class);
    }
}
