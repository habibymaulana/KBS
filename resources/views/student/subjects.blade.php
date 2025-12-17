<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Mata Pelajaran
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow border border-slate-100">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Ringkasan mata pelajaran aktif</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600">Kembali ke dashboard</a>
                </div>
                <div class="p-6">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-2 pr-4">Mata Pelajaran</th>
                                <th class="py-2 pr-4">Guru</th>
                                <th class="py-2 pr-4">Progres</th>
                                <th class="py-2 pr-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($subjects ?? [] as $subject)
                                <tr>
                                    <td class="py-3 pr-4 font-semibold text-gray-800">{{ $subject['name'] }}</td>
                                    <td class="py-3 pr-4 text-gray-600">{{ $subject['teacher'] }}</td>
                                    <td class="py-3 pr-4">
                                        <div class="w-40 h-2 bg-slate-100 rounded-full">
                                            <div class="h-2 rounded-full bg-gradient-to-r from-indigo-500 to-blue-500" style="width: {{ $subject['progress'] }}%"></div>
                                        </div>
                                    </td>
                                    <td class="py-3 pr-4 text-right">
                                        <button class="px-3 py-1 text-xs rounded-full bg-indigo-50 text-indigo-700 font-semibold">Lihat detail</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @empty($subjects)
                        <p class="text-sm text-gray-500 mt-4">Data mata pelajaran akan muncul di sini.</p>
                    @endempty
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


