<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Buat Tugas / Kuis
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow border border-slate-100 p-6">
                <form method="POST" action="{{ route('teacher.tasks.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="subject_id" :value="__('Mata Pelajaran')" />
                            <select id="subject_id" name="subject_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih mata pelajaran</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)">
                                        {{ $subject->name }}
                                        @if($subject->grade_level)
                                            ({{ $subject->grade_level }})
                                        @endif
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

                    <div>
                        <x-input-label for="title" :value="__('Judul Tugas / Kuis')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="type" :value="__('Tipe')" />
                            <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="tugas" @selected(old('type') === 'tugas')>Tugas</option>
                                <option value="kuis" @selected(old('type') === 'kuis')>Kuis</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('type')" />
                        </div>
                        <div>
                            <x-input-label for="due_date" :value="__('Jatuh Tempo')" />
                            <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" :value="old('due_date')" />
                            <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="notes" :value="__('Catatan untuk siswa (opsional)')" />
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Instruksi, link materi, dsb.">{{ old('notes') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('dashboard') }}" class="text-sm text-slate-600 hover:text-slate-800">Kembali ke dashboard</a>
                        <x-primary-button>
                            Simpan Tugas / Kuis
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


