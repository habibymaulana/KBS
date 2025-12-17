<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Buat Tugas / Kuis
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow border border-slate-100 p-6">
                <form method="POST" action="{{ route('teacher.tasks.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="subject_id" :value="__('Mata Pelajaran')" />
                            <select id="subject_id" name="subject_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih mata pelajaran</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('subject_id')" />
                        </div>

                        <div>
                            <x-input-label for="assigned_to" :value="__('Siswa')" />
                            <select id="assigned_to" name="assigned_to"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih siswa</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" @selected(old('assigned_to') == $student->id)>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('assigned_to')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="title" :value="__('Judul Tugas / Kuis')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                      :value="old('title')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="type" :value="__('Tipe')" />
                            <select id="type" name="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="tugas" @selected(old('type') === 'tugas')>Tugas</option>
                                <option value="kuis" @selected(old('type') === 'kuis')>Kuis</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('type')" />
                        </div>

                        <div>
                            <x-input-label for="due_date" :value="__('Jatuh Tempo')" />
                            <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full"
                                          :value="old('due_date')" />
                            <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="notes" :value="__('Catatan (opsional)')" />
                        <textarea id="notes" name="notes" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                  placeholder="Instruksi tambahan untuk siswa">{{ old('notes') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('dashboard') }}" class="text-sm text-slate-600 hover:text-slate-900">
                            Kembali ke dashboard
                        </a>
                        <x-primary-button>
                            Simpan Tugas / Kuis
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


