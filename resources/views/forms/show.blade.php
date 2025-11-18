<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $form->name }}</h1>

            @if($form->description)
                <p class="text-gray-600 mb-6">{{ $form->description }}</p>
            @endif

            @livewire('forms.form-submission-component', ['formId' => $form->id])
        </div>
    </div>
    @livewireScripts
</body>
</html>
