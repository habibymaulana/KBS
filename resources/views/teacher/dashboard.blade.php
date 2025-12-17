<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <p class="text-sm text-gray-500">Halo, {{ $user->name }}</p>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Dashboard Guru
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 uppercase tracking-wide">Guru</span>
                @if($user->subject_focus)
                    <span class="px-3 py-1 text-xs rounded-full bg-slate-100 text-slate-700">{{ $user->subject_focus }}</span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid md:grid-cols-3 gap-6">
                <div class="md:col-span-2 bg-gradient-to-r from-emerald-500 via-teal-500 to-blue-500 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute -left-8 bottom-0 w-32 h-32 bg-emerald-300/20 rounded-full blur-3xl"></div>
                    <p class="text-sm font-medium">Kelola kelas dengan cepat</p>
                    <h3 class="text-2xl font-semibold mt-2">Input nilai, tambah pelajaran, dan buat kuis</h3>
                    <p class="mt-2 text-white/80 max-w-xl">Semua di satu tempat dengan tampilan modern dan fokus pada aksi utama.</p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="{{ route('teacher.tasks.create') }}" class="px-4 py-2 bg-white text-emerald-700 rounded-xl text-sm font-semibold shadow hover:bg-slate-100 transition">
                            Buat Tugas Kelas
                        </a>
                        <a href="{{ route('teacher.quizzes.create') }}" class="px-4 py-2 bg-emerald-50 text-white rounded-xl text-sm font-semibold border border-white/50 hover:bg-white/10 transition">
                            Buat Kuis Pilihan Ganda
                        </a>
                        <a href="{{ route('teacher.subjects.create') }}" class="px-4 py-2 border border-white/40 rounded-xl text-sm hover:bg-white/10 transition">
                            Tambah Mata Pelajaran
                        </a>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow p-5 border border-slate-100">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-gray-800">Ringkasan</h4>
                        <span class="text-xs text-gray-500">hari ini</span>
                    </div>
                    <div class="space-y-3 text-sm text-gray-700">
                        <div class="flex items-center justify-between">
                            <span>Penilaian menunggu</span>
                            <span class="font-semibold text-emerald-600">6</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Kuis aktif</span>
                            <span class="font-semibold text-emerald-600">3</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Rata-rata kelas</span>
                            <span class="font-semibold text-emerald-600">82%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <div class="bg-white border border-slate-100 rounded-2xl shadow p-6 lg:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Input Nilai</h3>
                        <a href="#" class="text-sm text-emerald-600 font-medium">Ekspor</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 border-b">
                                    <th class="py-2 pr-4">Siswa</th>
                                    <th class="py-2 pr-4">Mata Pelajaran</th>
                                    <th class="py-2 pr-4">Tugas/Kuis</th>
                                    <th class="py-2 pr-4">Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($gradebook as $row)
                                    <tr class="align-middle">
                                        <td class="py-3 pr-4 font-semibold text-gray-800">{{ $row['student'] }}</td>
                                        <td class="py-3 pr-4">{{ $row['subject'] }}</td>
                                        <td class="py-3 pr-4">{{ $row['task'] }}</td>
                                        <td class="py-3 pr-4">
                                            <input type="number" value="{{ $row['score'] }}" class="w-20 rounded-md border-gray-200 focus:border-emerald-500 focus:ring-emerald-500" />
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="py-3 pr-4 font-semibold text-gray-800">Siswa baru</td>
                                    <td class="py-3 pr-4">
                                        <select class="rounded-md border-gray-200 focus:border-emerald-500 focus:ring-emerald-500">
                                            @foreach($subjects as $subject)
                                                <option>{{ $subject['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3 pr-4">
                                        <input type="text" placeholder="Judul tugas" class="w-48 rounded-md border-gray-200 focus:border-emerald-500 focus:ring-emerald-500" />
                                    </td>
                                    <td class="py-3 pr-4">
                                        <input type="number" placeholder="Nilai" class="w-20 rounded-md border-gray-200 focus:border-emerald-500 focus:ring-emerald-500" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Mata Pelajaran</h3>
                        <span class="text-xs text-gray-500">kelas aktif</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($subjects as $subject)
                            <div class="p-4 rounded-xl bg-gradient-to-r from-slate-50 to-white border border-slate-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $subject->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $subject->students_count }} siswa</p>
                                    </div>
                                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-emerald-100 text-emerald-700">Kelas aktif</span>
                                </div>
                            </div>
                        @endforeach
                        <a href="{{ route('teacher.subjects.create') }}" class="w-full inline-flex justify-center px-4 py-3 rounded-xl border border-dashed border-emerald-300 text-emerald-700 font-semibold hover:bg-emerald-50 transition">
                            + Tambah Pelajaran
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <div class="bg-white border border-slate-100 rounded-2xl shadow p-6 lg:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Kuis Pilihan Ganda</h3>
                        <a href="{{ route('teacher.quizzes.create') }}" class="text-sm text-emerald-600 font-medium">Buat / Kelola</a>
                    </div>
                    <div class="grid md:grid-cols-3 gap-4">
                        @foreach($quizIdeas as $quiz)
                            <div class="p-4 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 text-white shadow">
                                <p class="text-sm text-white/80">{{ $quiz['type'] }} • {{ $quiz['questions'] }} soal</p>
                                <h4 class="font-semibold text-lg mt-1">{{ $quiz['title'] }}</h4>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Profil Singkat</h3>
                        <a href="{{ route('profile.edit') }}" class="text-sm text-emerald-600 font-medium">Edit</a>
                    </div>
                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="flex items-center justify-between">
                            <span>Nama</span>
                            <span class="font-semibold">{{ $user->name }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Peran</span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 uppercase">Guru</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Mata Pelajaran</span>
                            <span class="font-semibold">{{ $user->subject_focus ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="flex items-start justify-between">
                            <span>Bio</span>
                            <span class="text-right text-gray-600 max-w-[200px]">{{ $user->bio ?? 'Tambahkan bio di halaman profil.' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

