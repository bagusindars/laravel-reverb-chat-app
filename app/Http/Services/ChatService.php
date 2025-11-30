<?php

namespace App\Http\Services;

use App\Enums\SupportConversationStatusEnum;
use App\Events\ConversationUpdateEvent;
use App\Events\MessageSentEvent;
use App\Events\NewConversationEvent;
use App\Models\GuestSupport;
use App\Models\Agent;
use App\Models\SupportConversation;
use App\Models\SupportMessage;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ChatService {
    public function registerUserLiveChat($name, $email, $phone)
    {
        try {
            DB::beginTransaction();

            // simulate create service desk
            $response = Http::get("https://randomuser.me/api");

            if (!$response->successful()) {
                throw new Exception("Failed generate sd");
            }

            $agent = $response->json()['results'][0];

            Agent::create([
                "name" => $agent['name']['title'] . ' ' . $agent['name']['first'] . ' ' . $agent['name']['last'],
                "email" => $agent['email'],
                "uuid" => $agent['login']['uuid'],
                "username" => $agent['login']['username'],
                "password" => $agent['login']['password'],
                "phone" => $agent['phone'],
                "cell" => $agent['cell'],
                "picture" => $agent['picture']['thumbnail'],
            ]);

            $guest = GuestSupport::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone
            ]);

            $session_id = Str::random(10);

            $this->setGuestSupportSession(session_id: $session_id);

            $conversation = SupportConversation::create([
                'session_id' => $session_id,
                'status' => SupportConversationStatusEnum::WAITING_AGENT,
                'guest_support_id' => $guest->id
            ]);

            NewConversationEvent::dispatch($conversation);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Succesfully start live chat',
                'data' => [
                    'session_id' => $session_id
                ]
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'data' => null,
            ];
        }
    }

    public function setGuestSupportSession($session_id)
    {
        Session::put("guest_support_session_id", $session_id);
    }

    public function getGuestSupportSessionId()
    {
        return Session::get("guest_support_session_id");
    }

    public function getConversation()
    {
        return SupportConversation::with(['guests'])->latest()->get();
    }

    public function findConversation($id)
    {
        return SupportConversation::with([
            'chats' => fn($q) => $q->orderBy('created_at', 'asc')->with('agents'),
            'guests',
        ])->find($id);
    }
    
    public function findConversationBySessionId($sesion_id)
    {
        return SupportConversation::with([
            'chats' => fn($q) => $q->orderBy('created_at', 'asc')->with('agents'),
            'guests',
        ])
        ->where('session_id', $sesion_id)
        ->where('status', '!=', 'closed')
        ->first();
    }
    

    public function sendMessage($conversation_id, $message, $type)
    {
        try {
            DB::beginTransaction();

            $agent_id = null;

            if ($type == 'agent') {
                $agent_id = Auth::guard('agent')->user()->id;
            }
            
            $message = SupportMessage::create([
                'content' => $message,
                'agent_id' => $agent_id,
                'support_conversation_id' => $conversation_id,
                'type' => $type
            ]);

            MessageSentEvent::dispatch($message);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Messsage sent',
                'data' => $message
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'data' => null,
            ];
        }
    }

    public function startConversation($conversation_id)
    {
        try {
            DB::beginTransaction();

            $conv = SupportConversation::with(['guests'])->find($conversation_id);

            $content =  'Halo ' . $conv->guests->name . '. Saya Agent BOT. Apa yang ingin kamu tanyakan?';
            
            $message = SupportMessage::create([
                'content' => $content,
                'agent_id' => null,
                'support_conversation_id' => $conversation_id,
                'type' => 'bot'
            ]);

            $conv->update([
                'status' => SupportConversationStatusEnum::ACTIVE->value
            ]);

            MessageSentEvent::dispatch($message);
            ConversationUpdateEvent::dispatch($conv);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Conversation started',
                'data' => [
                    'message' => $message
                ]
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ];
        }
    }

    public function closedConversation($conversation_id)
    {
        try {
            DB::beginTransaction();

            $conversation = SupportConversation::find($conversation_id);
            $conversation->update([
                'status' => SupportConversationStatusEnum::CLOSED->value
            ]);

            ConversationUpdateEvent::dispatch($conversation);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Conversation closed.',
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
        }
    }
}