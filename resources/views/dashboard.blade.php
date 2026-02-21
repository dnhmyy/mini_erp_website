<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center">
                <div class="p-3 rounded-lg bg-slate-50 mr-4">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-500">Total Permintaan</div>
                    <div class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center">
                <div class="p-3 rounded-lg bg-yellow-50 mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-500">Pending</div>
                    <div class="text-2xl font-bold text-slate-800">{{ $stats['pending'] }}</div>
                </div>
                <div class="ml-auto bg-yellow-100 h-1 w-10 rounded-full mt-1"></div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center">
                <div class="p-3 rounded-lg bg-green-50 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-500">Approved</div>
                    <div class="text-2xl font-bold text-slate-800">{{ $stats['approved'] }}</div>
                </div>
                <div class="ml-auto bg-green-100 h-1 w-10 rounded-full mt-1"></div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center">
                <div class="p-3 rounded-lg bg-red-50 mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-500">Rejected</div>
                    <div class="text-2xl font-bold text-slate-800">{{ $stats['rejected'] }}</div>
                </div>
                <div class="ml-auto bg-red-100 h-1 w-10 rounded-full mt-1"></div>
            </div>
        </div>

        <!-- Tabel Request -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Recent Requests</h3>
                <a href="{{ route('permintaan.index') }}" class="text-sm text-brand-primary font-semibold hover:underline">View All</a>
            </div>

            @if(auth()->user()->isSuperUser() || auth()->user()->isStaffGudang())
            <div class="p-4 border-b border-slate-100 bg-slate-50">
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="flex-1">
                        <label for="search" class="sr-only">Cari No Request</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari berdasarkan No Request..." class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                    </div>

                    <div class="w-full sm:w-64">
                        <label for="cabang_id" class="sr-only">Filter Cabang</label>
                        <select name="cabang_id" id="cabang_id" class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm bg-white">
                            <option value="">-- Semua Cabang --</option>
                            @foreach($cabangs as $c)
                                <option value="{{ $c->id }}" {{ request('cabang_id') == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cari & Filter
                    </button>
                    @if(request('search') || request('cabang_id'))
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 transition ease-in-out duration-150">
                        Reset
                    </a>
                    @endif
                </form>
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 font-semibold">No</th>
                            <th class="px-6 py-3 font-semibold">No Request</th>
                            <th class="px-6 py-3 font-semibold">Tanggal</th>
                            <th class="px-6 py-3 font-semibold">Cabang</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                            <th class="px-6 py-3 font-semibold">Dibuat Oleh</th>
                            <th class="px-6 py-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($requests as $request)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-600">{{ $requests->firstItem() + $loop->index }}</td>
                                <td class="px-6 py-4 font-medium text-slate-700">
                                    <a href="{{ route('permintaan.show', $request) }}" class="text-blue-800 hover:underline">
                                        {{ $request->no_request }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $request->tanggal }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $request->cabang->nama }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'approved' => 'bg-green-100 text-green-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            'shipped' => 'bg-purple-100 text-purple-700',
                                            'received_complete' => 'bg-green-100 text-green-700',
                                            'received_partial' => 'bg-orange-100 text-orange-700',
                                            'received' => 'bg-slate-800 text-white',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$request->status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ str_replace('_', ' ', ucfirst($request->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $request->user->name }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        @if(in_array($request->status, ['approved', 'shipped', 'received', 'received_complete', 'received_partial']))
                                            <a href="{{ route('permintaan.print.request', $request) }}" target="_blank" class="text-slate-400 hover:text-slate-600" title="Cetak">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                    No requests found yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
