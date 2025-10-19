<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Livewire Landing Page</title>
    @livewireStyles

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    {{ $slot }}
    @livewireScripts
</body>

</html>