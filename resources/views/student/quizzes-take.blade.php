<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Kuis: {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gradient-to-b from-violet-50 via-slate-50 to-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow border border-slate-100 p-6"
                 x-data="{ timeLeft: 60, submitting: false }"
                 x-init="
                    const interval = setInterval(() => {
                        if (timeLeft > 0) {
                            timeLeft--;
                        } else {
                            clearInterval(interval);
                            if (!submitting) {
                                submitting = true;
                                $refs.quizForm.submit();
                            }
                        }
                    }, 1000);
                 ">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $quiz->subject?->name }}</p>
                        <p class="text-xs text-slate-500">Jawab semua pertanyaan. Setiap jawaban salah akan mengurangi peluang poin penuh.</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500">Sisa waktu</p>
                        <p class="text-lg font-semibold text-rose-500" x-text="timeLeft + 's'"></p>
                    </div>
                </div>

                <form method="POST" action="{{ route('student.quizzes.submit', $quiz) }}" class="space-y-4" x-ref="quizForm">
                    @csrf

                    @foreach($quiz->questions as $index => $question)
                        <div class="rounded-xl border border-slate-200 p-4 space-y-2">
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $index + 1 }} / {{ $quiz->questions->count() }}. {{ $question->question_text }}
                            </p>
                            <div class="space-y-2">
                                @foreach($question->options as $option)
                                    <label class="flex items-center gap-2 text-sm text-slate-700">
                                        <input type="radio"
                                               name="answers[{{ $question->id }}]"
                                               value="{{ $option->id }}"
                                               class="text-indigo-500" />
                                        <span>{{ $option->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('student.quizzes') }}" class="text-sm text-slate-600 hover:text-slate-800">Kembali ke daftar kuis</a>
                        <x-primary-button>
                            Kirim Jawaban
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


