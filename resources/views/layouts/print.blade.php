<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Client Agreement' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        @media print {
            @page {
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .min-h-screen {
                min-height: 0 !important;
            }
            .p-6 {
                padding: 0 !important;
            }
        }
    </style>
    </head>
    <body class="bg-gray-100">
        <div class="min-h-screen p-6">
            <div class="max-w-5xl mx-auto">
                {{ $slot }}
            </div>
        </div>
        @livewireScripts
    </body>
    </html>
