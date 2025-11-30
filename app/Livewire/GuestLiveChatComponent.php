<?php

namespace App\Livewire;

use App\Enums\SupportConversationStatusEnum;
use App\Http\Services\ChatService;
use App\Models\SupportMessage;
use Livewire\Attributes\On;
use Livewire\Component;

class GuestLiveChatComponent extends Component
{
    public $name, $phone, $email, $captcha;

    public $captcha_src;

    public $session_id;

    public $conversation;

    public $message;

    protected ChatService $chatService;

    public function boot(
        ChatService $chatService
    )
    {
        $this->chatService = $chatService;
    }
    
    public function mount()
    {
        $this->session_id = $this->chatService->getGuestSupportSessionId();
        $this->loadConversation();
        $this->reloadCaptcha();
    }

    public function render()
    {
        return view('livewire.guest-live-chat-component');
    }

    public function reloadCaptcha()
    {
        $this->captcha_src = captcha_src();
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'phone' => ['required', 'string'],
            'captcha' => ['required', 'captcha']
        ];
    }

    public function submitRegistrationLiveChat()
    {
        $this->resetErrorBag();
        $this->validate(messages: [
            'captcha' => 'Captcha code does not match'
        ]);

        $payload = $this->chatService->registerUserLiveChat(
            name: $this->name,
            email: $this->email,
            phone: $this->phone
        );

        if ($payload['status']) {
           $this->session_id = $payload['data']['session_id'];
           $this->loadConversation();
           $this->dispatch('afterRegistered');
        }

        $this->reloadCaptcha();

        $this->dispatch('notify',
            type: $payload['status'] ? 'success' : 'error',
            content: $payload['message'],
        ); 

        return;
    }

    public function sendMessage()
    {
        if (!trim($this->message)) return;

         $payload = $this->chatService->sendMessage(
            conversation_id: $this->conversation['id'],
            message: $this->message,
            type: 'guest'
        );
        
        if ($payload['status']) {
            $this->message = null;
            $this->conversation->push($payload['data']);
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
        if ($conversation['id'] == $this->conversation?->id) {
            $this->conversation->status = SupportConversationStatusEnum::tryFrom($conversation['status']);
            $this->dispatch('conversationAssigned'); // to fe
        }  
    }

    #[On('noAgentFound')] // from fe
    public function closedConversation()
    {
        $payload = $this->chatService->closedConversation(conversation_id: $this->conversation?->id);

        $this->reset('name', 'email', 'phone', 'captcha');
        $this->reloadCaptcha();

        $this->dispatch('notify',
            type: 'error',
            content: $payload['message'],
        ); 

        return;
    }

    public function loadConversation()
    {
        if ($this->session_id) {
            $this->conversation = $this->chatService->findConversationBySessionId(sesion_id: $this->session_id);
        }
    }
}
