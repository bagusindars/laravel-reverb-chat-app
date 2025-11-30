<?php

namespace App\Enums;

enum SupportConversationStatusEnum: string
{
    case WAITING_AGENT = 'waiting_agent';
    case ACTIVE = 'active';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match($this) {
            self::WAITING_AGENT => 'Waiting for agent',
            self::ACTIVE => 'Active',
            self::CLOSED => 'Closed',
        };
    }
    
    public function color(): string
    {
        return match($this) {
            self::WAITING_AGENT => 'yellow',
            self::ACTIVE => 'green',
            self::CLOSED => 'red',
        };
    }
}
