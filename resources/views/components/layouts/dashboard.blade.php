<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])

    @livewireStyles
</head>

<body class="bg-background font-sans">
    <x-ui.layout :collapsable="false">
        <x-ui.sidebar>
            <x-slot:brand>
                <x-ui.brand href="{{ route('master-customer') }}" name="Workspace" />
            </x-slot:brand>
            <x-ui.navlist>
                <x-ui.navlist.item label="Master Customer" href="{{ route('master-customer') }}" />
                <x-ui.navlist.group label="Chat" variant="compact">
                    <x-ui.navlist.item label="Live Chat" href="{{ route('chat.live-chat') }}" />
                </x-ui.navlist.group>
            </x-ui.navlist>
        </x-ui.sidebar>

        <x-ui.layout.main>
            <x-ui.layout.header>
                <x-ui.sidebar.toggle class="md:hidden"/>
                <x-ui.navbar class="flex-1">
                    <h2 class="font-medium">{{ $title }}</h2>
                </x-ui.navbar>
                <div class="ml-auto flex items-center gap-4">
                    <x-ui.dropdown>
                        <x-slot:button class="justify-center">
                            <x-ui.avatar icon="user" color="indigo" circle />
                        </x-slot:button>
                        <x-slot:menu>
                            <x-ui.dropdown.group label="Signed in as">
                                <x-ui.dropdown.item>
                                    {{ auth('agent')->user()->name }}
                                </x-ui.dropdown.item>
                            </x-ui.dropdown.group>
                            <x-ui.dropdown.separator />
                            <form action="{{ route('logout') }}" method="post" class="contents">
                                @csrf
                                <x-ui.dropdown.item as="button" type="submit" variant="danger">
                                    Sign Out
                                </x-ui.dropdown.item>
                            </form>
                        </x-slot:menu>
                    </x-ui.dropdown>
                </div>
            </x-ui.layout.header>
            <div class="p-6">
                {{ $slot }}
            </div>
        </x-ui.layout.main>
    </x-ui.layout>

    <x-ui.toast />
    @vite(['resources/js/app.js'])

    @livewireScriptConfig
</body>

</html>