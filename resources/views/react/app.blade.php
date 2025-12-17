<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ruang Belajar Interaktif
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div id="react-root"></div>
        </div>
    </div>

    @vite('resources/js/react-app.tsx')
</x-app-layout>


