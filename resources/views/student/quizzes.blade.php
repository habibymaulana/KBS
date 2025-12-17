<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Kuis Visual
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-2xl shadow border border-slate-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm text-gray-500">Pilih kuis visual untuk mulai belajar</p>
                        <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600">Kembali ke dashboard</a>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach($quizzes ?? [] as $quiz)
                            <div class="p-4 rounded-xl text-white shadow {{ $quiz['color'] ?? 'bg-gradient-to-r from-indigo-500 to-blue-500' }}">
                                <p class="text-xs text-white/80 uppercase tracking-wide">{{ $quiz['type'] ?? 'Visual' }} • {{ $quiz['time'] ?? '10 menit' }}</p>
                                <h4 class="font-semibold text-lg mt-1">{{ $quiz['title'] ?? 'Kuis' }}</h4>
                                <button class="mt-3 px-3 py-1.5 rounded-full bg-white/15 text-xs font-semibold hover:bg-white/25 transition">
                                    Mulai Kuis
                                </button>
                            </div>
                        @endforeach
                        @empty($quizzes)
                            <p class="text-sm text-gray-500">Kuis akan muncul di sini.</p>
                        @endempty
                    </div>
                </div>
                <!-- Area tambahan untuk konten visual kuis bisa ditambahkan di sini nanti -->
            </div>
        </div>
    </div>
</x-app-layout>


