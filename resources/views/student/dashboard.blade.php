<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <p class="text-sm text-gray-500">Halo, {{ $user->name }}</p>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Dashboard Siswa
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700 uppercase tracking-wide">Siswa</span>
                @if($user->grade_level)
                    <span class="px-3 py-1 text-xs rounded-full bg-slate-100 text-slate-700">{{ $user->grade_level }}</span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-b from-violet-50 via-slate-50 to-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid md:grid-cols-3 gap-4">
                <!-- Panel jalur belajar -->
                <div class="md:col-span-2 bg-white/95 border border-violet-100 rounded-2xl px-6 py-5 shadow-sm shadow-violet-100">
                    <p class="text-xs font-semibold tracking-wide text-violet-600 uppercase">Jalur Belajar Visual</p>
                    <h3 class="text-xl font-semibold mt-2 text-slate-900">Tugas & kuis interaktif siap dikerjakan</h3>
                    <p class="mt-1 text-sm text-slate-500 max-w-xl">
                        Fokus pada tugas yang paling dekat deadline dan kuis visual yang direkomendasikan untukmu.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="{{ route('student.quizzes') }}" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-violet-600 text-white hover:bg-violet-700 transition">
                            Mulai Kuis Visual
                        </a>
                        <a href="{{ route('student.tasks') }}" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
                            Lihat Tugas
                        </a>
                    </div>
                </div>

                <!-- Panel statistik singkat -->
                <div class="bg-white/95 rounded-2xl shadow-sm p-5 border border-violet-100">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-slate-900 text-sm">Statistik singkat</h4>
                        <span class="text-xs text-slate-500">minggu ini</span>
                    </div>
                    <div class="space-y-4">
                        @foreach($stats as $item)
                            <div>
                                <div class="flex items-center justify-between text-xs font-medium text-slate-700">
                                    <span>{{ $item['label'] }}</span>
                                    <span>{{ $item['value'] }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-1.5 rounded-full mt-1">
                                    <div class="h-1.5 rounded-full bg-violet-500" style="width: {{ $item['value'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 bg-white/95 border border-slate-200 rounded-2xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Tugas Terbaru</h3>
                        <a href="{{ route('student.tasks') }}" class="text-sm text-indigo-600 font-medium">Lihat semua</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($tasks as $task)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $task['title'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $task['subject'] }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-500">Due: {{ $task['due'] }}</span>
                                    @php
                                        $pill = match($task['status']) {
                                            'Selesai' => 'bg-emerald-100 text-emerald-700',
                                            'Progres' => 'bg-amber-100 text-amber-700',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $pill }}">{{ $task['status'] }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-sm text-gray-500">
                                Belum ada tugas. Tugas dari guru akan muncul di sini.
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="bg-white/95 border border-slate-200 rounded-2xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Pencapaian</h3>
                        <span class="text-xs text-gray-500">Live</span>
                    </div>
                    <div class="space-y-3">
                        @forelse($achievements as $ach)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">{{ $ach['badge'] }}</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $ach['score'] }} pts</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $ach['desc'] }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Pencapaian kamu akan tampil di sini setelah menyelesaikan kuis dan tugas.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-4">
                <div class="bg-white/95 border border-slate-200 rounded-2xl shadow-sm p-6 lg:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Mata Pelajaran</h3>
                        <a href="{{ route('student.subjects') }}" class="text-sm text-indigo-600 font-medium">Lihat jadwal</a>
                    </div>
                    <div class="grid md:grid-cols-3 gap-3">
                        @forelse($subjects as $subject)
                            <div class="p-4 rounded-xl text-white shadow bg-gradient-to-br {{ $subject['color'] }}">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold">{{ $subject['name'] }}</h4>
                                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">{{ $subject['teacher'] }}</span>
                                </div>
                                <p class="text-sm mt-1 text-white/80">Progres {{ $subject['progress'] }}%</p>
                                <div class="w-full h-2 bg-white/30 rounded-full mt-2">
                                    <div class="h-2 rounded-full bg-white" style="width: {{ $subject['progress'] }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 col-span-3">Belum ada mata pelajaran yang terdaftar. Guru akan menambahkan kelas untukmu.</p>
                        @endforelse
                    </div>
                </div>
                <div class="bg-white/95 border border-slate-200 rounded-2xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Kuis Visual</h3>
                        <span class="text-xs text-gray-500">Practice</span>
                    </div>
                    <div class="space-y-3">
                        @forelse($quizzes as $quiz)
                            <div class="p-4 rounded-xl text-white shadow {{ $quiz['color'] }}">
                                <p class="text-sm text-white/80">{{ $quiz['type'] }} • {{ $quiz['time'] }}</p>
                                <h4 class="font-semibold text-lg mt-1">{{ $quiz['title'] }}</h4>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada kuis aktif. Nantinya kuis dari guru akan muncul di sini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

