<!DOCTYPE html>
<html class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
    </head>
    
    <body class="min-h-screen bg-gradient-to-br from-[#053B50] via-[#176B87] to-[#64CCC5]">
        @inertia
    </body>
</html>
