<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Buat Kuis Pilihan Ganda
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow border border-slate-100 p-6"
                 x-data="quizBuilder()">
                <form method="POST" action="{{ route('teacher.quizzes.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="subject_id" :value="__('Mata Pelajaran')" />
                            <select id="subject_id" name="subject_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih mata pelajaran</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)">
                                        {{ $subject->name }} @if($subject->grade_level) ({{ $subject->grade_level }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('subject_id')" />
                        </div>

                        <div>
                            <x-input-label for="grade_level" :value="__('Kelas sasaran')" />
                            <select id="grade_level" name="grade_level" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih kelas</option>
                                @foreach($gradeLevels as $grade)
                                    <option value="{{ $grade }}" @selected(old('grade_level') === $grade)>{{ $grade }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('grade_level')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <x-input-label for="title" :value="__('Judul Kuis')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>
                        <div>
                            <x-input-label for="due_at" :value="__('Jatuh Tempo')" />
                            <x-text-input id="due_at" name="due_at" type="datetime-local" class="mt-1 block w-full" :value="old('due_at')" />
                            <x-input-error class="mt-2" :messages="$errors->get('due_at')" />
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-800">Pertanyaan Pilihan Ganda</h3>
                            <button type="button" class="text-xs text-emerald-600 font-semibold" x-on:click="addQuestion()">
                                + Tambah pertanyaan
                            </button>
                        </div>

                        <template x-if="questions.length === 0">
                            <p class="text-xs text-slate-500">Belum ada pertanyaan. Tambahkan minimal 1 pertanyaan.</p>
                        </template>

                        <div class="space-y-4">
                            <template x-for="(q, qIndex) in questions" :key="qIndex">
                                <div class="rounded-xl border border-slate-200 p-4 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-semibold text-slate-700">Pertanyaan <span x-text="qIndex + 1"></span></p>
                                        <button type="button" class="text-[11px] text-rose-500" x-on:click="removeQuestion(qIndex)">Hapus</button>
                                    </div>
                                    <div>
                                        <x-input-label :value="__('Teks pertanyaan')" />
                                        <textarea class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs"
                                                  x-model="q.text"></textarea>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-[11px] text-slate-600">Pilihan jawaban (pilih satu yang benar)</p>
                                        <template x-for="(opt, oIndex) in q.options" :key="oIndex">
                                            <label class="flex items-center gap-2 text-xs">
                                                <input type="radio"
                                                       class="text-emerald-500"
                                                       :name="`questions[${qIndex}][correct_index]`"
                                                       :value="oIndex"
                                                       x-model.number="q.correct_index" />
                                                <input type="text"
                                                       class="flex-1 rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                                       x-model="opt.text" />
                                            </label>
                                        </template>
                                    </div>

                                    <!-- Hidden inputs to submit to backend -->
                                    <template x-for="(opt, oIndex) in q.options" :key="`hidden-${oIndex}`">
                                        <input type="hidden"
                                               :name="`questions[${qIndex}][options][${oIndex}][text]`"
                                               :value="opt.text">
                                    </template>
                                    <input type="hidden"
                                           :name="`questions[${qIndex}][text]`"
                                           :value="q.text">
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('dashboard') }}" class="text-sm text-slate-600 hover:text-slate-800">Kembali ke dashboard</a>
                        <x-primary-button>
                            Simpan Kuis
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function quizBuilder() {
            return {
                questions: [
                    {
                        text: '',
                        correct_index: 0,
                        options: [
                            { text: '' },
                            { text: '' },
                            { text: '' },
                            { text: '' },
                        ],
                    },
                ],
                addQuestion() {
                    this.questions.push({
                        text: '',
                        correct_index: 0,
                        options: [
                            { text: '' },
                            { text: '' },
                            { text: '' },
                            { text: '' },
                        ],
                    });
                },
                removeQuestion(index) {
                    this.questions.splice(index, 1);
                },
            };
        }
    </script>
</x-app-layout>


