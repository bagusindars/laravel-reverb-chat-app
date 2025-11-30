<div>
    <div class="flex h-screen">
        <div class="w-1/4 border-r border-gray-200 overflow-y-auto pr-3">
            <div class="flex flex-col gap-2">
                <div class="space-y-4">
                    @foreach($conversations as $cv)
                    <div class="border border-dotted border-gray-300 rounded-xl p-4 shadow-sm hover:shadow-md transition {{ $cv['id'] == $conversation_selected_id ? 'bg-blue-50' : 'bg-white' }}">
                        <div class="flex items-center justify-between flex-wrap">
                            <h3 class="font-semibold text-lg text-gray-800">
                                {{ $cv['guests']['name'] }}
                            </h3>
                            <x-ui.badge variant="outline" color="{{ $cv['status']->color() }}" size="sm" class="mt-2">{{ $cv['status']->label() }}</x-ui.badge>
                        </div>

                        <div class="mt-3 text-sm text-gray-700 space-y-1">
                            <p><span class="font-medium text-gray-900">Email:</span> {{ $cv['guests']['email'] }}</p>
                            <p><span class="font-medium text-gray-900">Create Date:</span> {{ $cv['guests']['created_at'] }}
                            </p>
                            <p><span class="font-medium text-gray-900">Phone:</span> {{ $cv['guests']['phone'] }}</p>
                        </div>

                        <div class="mt-4">
                            <x-ui.button size="sm" class="w-full" wire:target="viewConversation('{{ $cv['id'] }}')"
                                wire:click="viewConversation('{{ $cv['id'] }}')"> View 
                            </x-ui.button>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>

        <livewire:dashboard.chat-component conversation_id="{{ $conversation_selected_id }}"
            wire:key="conv-{{ $conversation_selected_id }}" />
    </div>
</div>