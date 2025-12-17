<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Tambah Mata Pelajaran
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow border border-slate-100 p-6">
                <form method="POST" action="{{ route('teacher.subjects.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nama Mata Pelajaran')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="class_code" :value="__('Kode Kelas (opsional)')" />
                            <x-text-input id="class_code" name="class_code" type="text" class="mt-1 block w-full" :value="old('class_code')" placeholder="mis. MATH-X-1" />
                            <x-input-error class="mt-2" :messages="$errors->get('class_code')" />
                        </div>

                        <div>
                            <x-input-label for="grade_level" :value="__('Kelas')" />
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
                        <x-input-label for="description" :value="__('Deskripsi (opsional)')" />
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Ringkasan materi yang diajarkan">{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('dashboard') }}" class="text-sm text-slate-600 hover:text-slate-800">Kembali ke dashboard</a>
                        <x-primary-button>
                            Simpan Mata Pelajaran
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


