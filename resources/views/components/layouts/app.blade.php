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

<body class="font-sans bg-background">
    <main>
        {{ $slot }}
    </main>

    <x-ui.toast />

    <livewire:guest-live-chat-component />

    @vite(['resources/js/app.js'])

    @livewireScriptConfig
</body>

</html>