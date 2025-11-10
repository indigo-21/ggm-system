<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        {{-- <div class="flex justify-between w-screen">
            <div class="w-3/5 min-h-screen">
                <img  class="w-full min-h-screen" src="{{asset('login1.jpg')}}" alt="">
            </div>

            <div class="w-2/5 min-h-screen flex justify-center items-center">
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div> --}}
        <div class="flex">
            <div class="" style="width:60%;">
                <img  class="w-full min-h-screen" src="{{asset('login1.jpg')}}" alt="">
            </div>
            <div class="flex items-center" style="width:40%;">
                <div class="mx-auto items-center rounded-xl bg-white p-6 shadow-lg outline outline-black/5 dark:bg-[#b7b88c] dark:shadow-none dark:-outline-offset-1 dark:outline-white/10 rounded-md" style="width:80%;background-color:#b7b88c">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
