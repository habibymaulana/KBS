<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Pencapaian & Statistik Nilai
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-gray-500">Ringkasan mirip SIAK: nilai per mata pelajaran</p>
                    <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600">Kembali ke dashboard</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-2 px-3">Mata Pelajaran</th>
                                <th class="py-2 px-3">SKS</th>
                                <th class="py-2 px-3">Nilai</th>
                                <th class="py-2 px-3">Setara</th>
                                <th class="py-2 px-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach(($grades ?? []) as $row)
                                <tr>
                                    <td class="py-3 px-3 font-semibold text-gray-800">{{ $row['subject'] }}</td>
                                    <td class="py-3 px-3 text-gray-600">{{ $row['credit'] }}</td>
                                    <td class="py-3 px-3 text-gray-800">{{ $row['score'] }}</td>
                                    <td class="py-3 px-3 text-gray-600">{{ $row['letter'] }}</td>
                                    <td class="py-3 px-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $row['status_color'] ?? 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $row['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @empty($grades)
                        <p class="text-sm text-gray-500 mt-4">Data nilai akan muncul di sini.</p>
                    @endempty
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


