<div class="relative">
    <div class="flex justify-center items-center w-full h-screen">
        <form wire:submit="submit" class="flex flex-col gap-6 shadow-2xl px-5 py-10 rounded-lg w-[350px] bg-white">
            <x-ui.field required>
                <x-ui.label text="Email" />
                <x-ui.input wire:model="email" type="email" required autofocus autocomplete="email"
                    placeholder="email@example.com" />

                <x-ui.error name="email" />
            </x-ui.field>
            <x-ui.field required>
                <x-ui.label text="Password" />
                <x-ui.input wire:model="password" type="password" required autocomplete="current-password"
                    placeholder="Password" revealable />

                <x-ui.error name="password" />
            </x-ui.field>
            <x-ui.button type="submit" class="w-full mt-5">
                Login
            </x-ui.button>
        </form>
    </div>
</div>