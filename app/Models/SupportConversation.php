<?php

namespace App\Models;

use App\Enums\SupportConversationStatusEnum;
use Illuminate\Database\Eloquent\Model;

class SupportConversation extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => SupportConversationStatusEnum::class,
        ];
    }

    public function guests()
    {
        return $this->hasOne(GuestSupport::class, 'id', 'guest_support_id');
    }

    public function chats()
    {
        return $this->hasMany(SupportMessage::class, 'support_conversation_id', 'id');
    }
}
