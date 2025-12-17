<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Kuis Kelas Kamu
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-gray-500">Pilih kuis yang sudah dibuat guru untuk kelasmu.</p>
                    <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600">Kembali ke dashboard</a>
                </div>

                <div class="space-y-3">
                    @forelse($quizzes as $quiz)
                        @php
                            $attempt = $attempts[$quiz->id] ?? null;
                            $percent = $attempt && $attempt->max_score > 0
                                ? round(($attempt->score / $attempt->max_score) * 100)
                                : null;
                        @endphp
                        <div class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $quiz->title }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $quiz->subject?->name }} •
                                    Kelas {{ $quiz->grade_level ?? '-' }}
                                </p>
                                @if($attempt)
                                    <p class="text-xs text-emerald-600 mt-1">
                                        Sudah dikerjakan • Skor: {{ $attempt->score }}/{{ $attempt->max_score }}
                                        ({{ $percent }}%)
                                    </p>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 text-xs">
                                @if($quiz->due_at)
                                    <span class="text-slate-500">Jatuh tempo: {{ $quiz->due_at->format('d M H:i') }}</span>
                                @endif
                                @if($attempt)
                                    <a href="{{ route('student.quizzes.result', $attempt) }}"
                                       class="px-3 py-1.5 rounded-full border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition">
                                        Lihat hasil
                                    </a>
                                @else
                                    <a href="{{ route('student.quizzes.show', $quiz) }}"
                                       class="px-3 py-1.5 rounded-full bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                                        Mulai Kuis
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada kuis untuk kelasmu.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


