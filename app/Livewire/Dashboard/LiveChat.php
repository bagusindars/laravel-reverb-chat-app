<?php

namespace App\Livewire\Dashboard;

use App\Enums\SupportConversationStatusEnum;
use App\Http\Services\ChatService;
use App\Models\GuestSupport;
use App\Models\SupportConversation;
use Livewire\Attributes\On;
use Livewire\Component;

class LiveChat extends Component
{
    public $conversations;

    public $message;

    public $conversation_selected_id;

    protected ChatService $chatService;

    public function boot(
        ChatService $chatService
    )
    {
        $this->chatService = $chatService;
    }

    public function mount()
    {
        $this->conversations = $this->chatService->getConversation();
    }

    public function render()
    {
        return view('livewire.dashboard.live-chat')->layout('components.layouts.dashboard')->layoutData([
            'title' => 'Live Chat'
        ]);
    }

    public function viewConversation($id)
    {
        $this->conversation_selected_id = $id;
    }

    
    #[On('echo:update-conversation,ConversationUpdateEvent')]
    public function updateConversationStatus($conversation)
    {
        $this->conversations = $this->conversations->map(function ($item) use ($conversation) {
            if ($item['id'] === $conversation['id']) {
                $item['status'] = SupportConversationStatusEnum::tryFrom($conversation['status']);
            }
            return $item;
        });
    }

    #[On('echo:new-conversation,NewConversationEvent')]
    public function newConversation($conversation)
    {
        $cv_model = SupportConversation::make(
            collect($conversation['conversation'])->except('guests')->toArray()
        );

        $cv_model->setRelation(
            'guests',
            GuestSupport::make($conversation['conversation']['guests'])
        );

        $this->conversations->prepend($cv_model);
    }
}
