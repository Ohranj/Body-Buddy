<html class="min-h-full">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @inertiaHead
</head>

<body class="p-2 sm:p-4 bg-zinc-900 text-white">
    @inertia
</body>

</html>