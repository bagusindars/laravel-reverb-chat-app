<?php

namespace App\Livewire\Dashboard;

use App\Enums\SupportConversationStatusEnum;
use App\Http\Services\ChatService;
use App\Models\Agent;
use App\Models\SupportMessage;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatComponent extends Component
{
    public $conversation;

    public $message;
    
    protected ChatService $chatService;

    public function boot(
        ChatService $chatService
    )
    {
        $this->chatService = $chatService;
    }
    
    public function mount($conversation_id = null)
    {
        if ($conversation_id) {
            $this->conversation = $this->chatService->findConversation($conversation_id);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.chat-component');
    }

    public function submitChat()
    {
        if ($this->conversation['status']->value == 'closed') {
            $this->dispatch('notify',
                type: 'error',
                content: 'Conversation has been closed!',
            ); 
            return;
        }

        if ($this->conversation['status']->value == 'waiting_agent') {
            $payload = $this->chatService->startConversation(
                conversation_id: $this->conversation['id'],
            );

            if ($payload['status']) {
                $this->conversation->status = SupportConversationStatusEnum::ACTIVE;
                $this->conversation->push($payload['data']['message']);
            }
        } else {
            if (!trim($this->message)) return;
            $payload = $this->chatService->sendMessage(
                conversation_id: $this->conversation['id'],
                message: $this->message,
                type: 'agent'
            );

            if ($payload['status']) {
                $this->message = null;
                $this->conversation->push($payload['data']);
            }
        }
    }

    #[On('echo:support-conversation,MessageSentEvent')]
    public function newMessage($message)
    {
        if ($message['support_conversation_id'] == $this->conversation?->id) {
            $msg = SupportMessage::find($message['id']);
            $this->conversation->push($msg);
        }
    }

    #[On('echo:update-conversation,ConversationUpdateEvent')]
    public function updateConversationStatus($conversation)
    {
        if ($this->conversation?->id == $conversation['id']) {
            $this->conversation->status = SupportConversationStatusEnum::tryFrom($conversation['status']);
        }
    }

    public function closedConversation()
    {
        $payload = $this->chatService->closedConversation(conversation_id: $this->conversation?->id);

        $this->reset('message');
        $this->dispatch('notify',
            type: $payload['status'] ? 'success' : 'error',
            content: $payload['message'],
        ); 

        return;
    }
}
