<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Catatan Tugas
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow border border-slate-100">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <p class="text-sm text-gray-500">Daftar tugas/quiz dari guru</p>
                    <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600">Kembali ke dashboard</a>
                </div>
                <div class="p-6">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-2 pr-4">Judul</th>
                                <th class="py-2 pr-4">Mata Pelajaran</th>
                                <th class="py-2 pr-4">Jatuh Tempo</th>
                                <th class="py-2 pr-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($tasks ?? [] as $task)
                                <tr>
                                    <td class="py-3 pr-4 font-semibold text-gray-800">{{ $task['title'] }}</td>
                                    <td class="py-3 pr-4 text-gray-600">{{ $task['subject'] }}</td>
                                    <td class="py-3 pr-4 text-gray-600">{{ $task['due'] }}</td>
                                    <td class="py-3 pr-4">
                                        @php
                                            $pill = match($task['status']) {
                                                'Selesai' => 'bg-emerald-100 text-emerald-700',
                                                'Progres' => 'bg-amber-100 text-amber-700',
                                                default => 'bg-slate-100 text-slate-700',
                                            };
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $pill }}">{{ $task['status'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @empty($tasks)
                        <p class="text-sm text-gray-500 mt-4">Belum ada tugas tercatat.</p>
                    @endempty
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


