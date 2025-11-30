<div class="flex-1 relative">
    <div class="h-full relative">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold mb-4 p-3">Chat</h2>
            @if ($conversation && $conversation->status->value == 'active')
                <x-ui.button wire:click="closedConversation" variant="danger" type="button" class="w-fit" size="xs">
                    Closed Conversation
                </x-ui.button>
            @endif
        </div>
        @if ($conversation)
            <div class="flex flex-col gap-2 py-5 overflow-y-auto max-h-[650px]">
                @forelse($conversation->chats as $chat)
                    @if ($chat->type === 'guest')
                        <div wire:key="chatt-{{ $chat->id }}" class="flex items-start justify-start py-2 px-5 gap-2">
                            <div class="shrink-0">
                                <x-ui.icon name="user-circle" class="text-primary size-7" />
                            </div>
                            <div class="flex flex-col">
                                <div class="border border-gray-100 rounded-xl p-2 bg-gray-100">
                                    <p class="font-medium text-sm">{{ $conversation->guests->name }}</p>
                                    <p class="text-sm">{{ $chat->content }}</p>
                                </div>
                                <p class="text-xs mt-2">{{ $chat->created_at }}</p>
                            </div>
                        </div>
                    @else
                        <div wire:key="chatt-{{ $chat->id }}" class="flex items-start justify-end py-2 px-5 gap-2">
                            <div class="flex flex-col items-end">
                                <div class="border border-gray-100 rounded-xl p-2 bg-blue-200">
                                    <p class="font-medium text-sm">{{ $chat->type == 'agent' ? 'Agent : ' . $chat->agents->name : 'Agent : BOT' }}</p>
                                    <p class="text-sm">{{ $chat->content }}</p>
                                </div>
                                <p class="text-xs mt-2">{{ $chat->created_at }}</p>
                            </div>
                            <div class="shrink-0">
                                <x-ui.icon name="user-circle" class="text-primary size-7" />
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-center text-gray-400 font-medium text-md italic">No conversation</p>
                @endforelse
            </div>

            <form class="p-5 flex items-center gap-3 absolute bottom-150px w-full left-0" wire:submit="submitChat">
                @if ($conversation->status->value == 'active')
                        <x-ui.field>
                            <x-ui.textarea wire:model="message" resize="none" class="min-h-24 max-h-24" placeholder="Type something..." />
                            <x-ui.error name="message" />
                        </x-ui.field>
                        <x-ui.button type="submit" class="w-fit">
                            <x-ui.icon name="paper-airplane" class="text-white" />
                        </x-ui.button>
                    </form>
                @elseif ($conversation->status->value == 'closed')
                    <div class="flex justify-center w-full">
                        <p class="text-red-400 font-medium text-md border border-dotted border-red-400 p-5">Conversation has been closed</p>
                    </div>
                @elseif ($conversation->status->value == 'waiting_agent')
                    <div class="flex justify-center w-full">
                        <x-ui.button type="submit" class="w-fit">
                            Start Conversation
                        </x-ui.button>
                    </div>
                @endif
            </form>
        @else
            <p class="text-center text-gray-400 font-medium text-md">Select conversation first</p>
        @endif
    </div>
</div>