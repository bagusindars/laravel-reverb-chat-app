<div class="fixed bottom-10 right-10">
    <x-ui.popover>
        <x-ui.popover.trigger>
            <x-ui.button class="shadow-lg bg-white" variant="solid">
                <x-ui.icon name="question-mark-circle" class="text-primary" />
            </x-ui.button>
        </x-ui.popover.trigger>
        <x-ui.popover.overlay position="top" :offset="8" class="w-sm! p-0">
            <div class="flex justify-center items-center p-3 bg-primary">
                <p class="text-white">Live Chat Support</p>
            </div>
            @if ($conversation && $conversation->status->value != 'closed')
                @if ($conversation->status->value == 'active')
                    <div class="flex flex-col gap-2 py-5 overflow-y-auto max-h-96">
                        @forelse ( $conversation->chats as $chat)
                            @if ($chat->type != 'guest')
                                <div class="flex items-start justify-start py-2 px-5 gap-2">
                                    <div class="shrink-0">
                                        <x-ui.icon name="user-circle" class="text-primary size-7" />
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="border border-gray-100 rounded-xl p-2 bg-gray-100">
                                            <p class="font-medium text-sm">{{ $chat->type == 'bot' ? 'Agent : BOT' : 'Agent : ' . $chat->agents->name }}</p>
                                            <p class="text-sm">{{ $chat->content }}</p>
                                        </div>
                                        <p class="text-xs mt-2">{{ $chat->created_at }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-start justify-end py-2 px-5 gap-2">
                                    <div class="flex flex-col items-end">
                                        <div class="border border-gray-100 rounded-xl p-2 bg-blue-200">
                                            <p class="font-medium text-sm">{{ $conversation->guests->name }}</p>
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
                            <p class="font-medium italic text-center text-sm">No chat has started</p>
                        @endforelse
                    </div>
                    <form class="p-5 flex items-center gap-3" wire:submit="sendMessage">
                        <x-ui.field>
                            <x-ui.textarea wire:model="message" resize="none" class="min-h-24 max-h-24" />
                        </x-ui.field>
                        <x-ui.button type="submit" class="w-fit">
                            <x-ui.icon name="paper-airplane" class="text-white" />
                        </x-ui.button>
                    </form>
                @else
                     <div class="bg-gray-100 p-5">
                        <p class="text-sm text-center">Okay, take a deep breath and release!</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><circle fill="#051601" stroke="#051601" stroke-width="2" r="15" cx="40" cy="65"><animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.4"></animate></circle><circle fill="#051601" stroke="#051601" stroke-width="2" r="15" cx="100" cy="65"><animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.2"></animate></circle><circle fill="#051601" stroke="#051601" stroke-width="2" r="15" cx="160" cy="65"><animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="0"></animate></circle></svg>
                    <div class="bg-gray-100 p-5 mt-5">
                        <p class="text-sm text-center">We're looking for the best customer representative for you</p>
                    </div>
                @endif
            @else
                <form class="p-5 flex flex-col gap-2" wire:submit="submitRegistrationLiveChat">
                    <x-ui.field required>
                        <x-ui.label text="Name" />
                        <x-ui.input wire:model="name" type="text" required placeholder="John doe" />
                        <x-ui.error name="name" />
                    </x-ui.field>
                    <x-ui.field required>
                        <x-ui.label text="Email" />
                        <x-ui.input wire:model="email" type="email" required placeholder="email@example.com" />
                        <x-ui.error name="email" />
                    </x-ui.field>
                    <x-ui.field required>
                        <x-ui.label text="Phone" />
                        <x-ui.input wire:model="phone" type="text" required placeholder="08xxxx" />
                        <x-ui.error name="phone" />
                    </x-ui.field>
                    <x-ui.field required>
                        <div class="flex justify-center items-center gap-2 m-3">
                            <img src="{{ $captcha_src }}" alt="captcha" class="w-[80%]">
                            <x-ui.button type="button" wire:click="reloadCaptcha" size="xs" variant="ghost">
                                <x-ui.icon name="arrow-path" class="text-primary size-5"   wire:click="reloadCaptcha" />
                            </x-ui.button>
                        </div>
                        <x-ui.label text="Captcha" />
                        <x-ui.input wire:model="captcha" type="text" required />
                        <x-ui.error name="captcha" />
                    </x-ui.field>
                    <x-ui.button type="submit" class="w-fit mt-3 ms-auto" size="sm">
                        Next
                    </x-ui.button>
                </form>
            @endif
        </x-ui.popover.overlay>
    </x-ui.popover>

    <script>
        document.addEventListener('livewire:init', () => {
            let timeoutId = null;

            Livewire.on('afterRegistered', (event) => {
                timeoutId = setTimeout(() => {
                    clearTimeout(timeoutId);
                    Livewire.dispatch('noAgentFound')
                }, 180000); // 3 minute
            });

            Livewire.on('conversationAssigned', (event) => {
                if (timeoutId) {
                    clearTimeout(timeoutId);
                }
            });
        });
    </script>
</div>