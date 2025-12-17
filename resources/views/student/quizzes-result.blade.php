<x-app-layout>
    @php
        $quiz = $attempt->quiz;
        $percent = $attempt->max_score > 0 ? round(($attempt->score / $attempt->max_score) * 100) : 0;
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Hasil Kuis: {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $quiz->subject?->name }}</p>
                        <p class="text-xs text-slate-500">Jawaban benar: {{ $attempt->correct_count }}, salah: {{ $attempt->wrong_count }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500">Skor kamu</p>
                        <p class="text-xl font-semibold text-emerald-600">{{ $attempt->score }} / {{ $attempt->max_score }} ({{ $percent }}%)</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($quiz->questions as $question)
                        @php
                            $answer = $attempt->answers->firstWhere('question_id', $question->id);
                            $selected = $answer?->option_id;
                            $correctOption = $question->options->firstWhere('is_correct', true);
                        @endphp
                        <div class="rounded-xl border border-slate-200 p-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-900">{{ $question->question_text }}</p>
                                @if($answer?->is_correct)
                                    <span class="text-xs font-semibold text-emerald-600">Benar</span>
                                @else
                                    <span class="text-xs font-semibold text-rose-600">Salah</span>
                                @endif
                            </div>
                            <div class="space-y-1 text-sm">
                                @foreach($question->options as $option)
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full
                                            @if($option->id === $correctOption?->id) bg-emerald-500
                                            @elseif($option->id === $selected) bg-rose-500
                                            @else bg-slate-300 @endif">
                                        </span>
                                        <span
                                            @class([
                                                'font-semibold text-emerald-700' => $option->id === $correctOption?->id,
                                                'text-rose-600' => $option->id === $selected && $selected !== $correctOption?->id,
                                                'text-slate-700' => $option->id !== $selected && $option->id !== $correctOption?->id,
                                            ])
                                        >
                                            {{ $option->option_text }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <a href="{{ route('student.quizzes') }}" class="text-sm text-slate-600 hover:text-slate-800">Kembali ke daftar kuis</a>
                    <div class="space-x-2">
                        <a href="{{ route('student.quizzes.show', $quiz) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Kerjakan lagi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


