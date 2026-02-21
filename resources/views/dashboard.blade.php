<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center transition-all hover:shadow-md">
                <div class="p-3 rounded-lg bg-slate-50 mr-4">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Permintaan</div>
                    <div class="text-2xl font-black text-slate-800">{{ $stats['total'] }}</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center transition-all hover:shadow-md">
                <div class="p-3 rounded-lg bg-amber-50 mr-4">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-amber-400 uppercase tracking-wider">Pending</div>
                    <div class="text-2xl font-black text-slate-800">{{ $stats['pending'] }}</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center transition-all hover:shadow-md">
                <div class="p-3 rounded-lg bg-emerald-50 mr-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Approved</div>
                    <div class="text-2xl font-black text-slate-800">{{ $stats['approved'] }}</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center transition-all hover:shadow-md">
                <div class="p-3 rounded-lg bg-rose-50 mr-4">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-rose-400 uppercase tracking-wider">Rejected</div>
                    <div class="text-2xl font-black text-slate-800">{{ $stats['rejected'] }}</div>
                </div>
            </div>
        </div>

        <!-- Tabel Request -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <h3 class="font-bold text-slate-800 tracking-tight flex items-center">
                    <svg class="w-5 h-5 mr-2 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Recent Requests
                </h3>
                <a href="{{ route('permintaan.index') }}" class="text-xs font-bold text-brand-primary hover:text-brand-secondary transition uppercase tracking-widest px-3 py-1.5 bg-brand-primary/5 rounded-lg">View All</a>
            </div>

            @if(auth()->user()->isSuperUser() || auth()->user()->isStaffGudang())
            <div class="p-4 border-b border-slate-100 bg-white">
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari No Request..." 
                               autocomplete="off"
                               @input.debounce.500ms="$el.form.submit()"
                               class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm transition-all duration-200">
                    </div>

                    <div class="w-full sm:w-64">
                        <select name="cabang_id" 
                                onchange="this.form.submit()"
                                class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm bg-white font-medium text-slate-600">
                            <option value="">-- Semua Cabang --</option>
                            @foreach($cabangs as $c)
                                <option value="{{ $c->id }}" {{ request('cabang_id') == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if(request('search') || request('cabang_id'))
                    <a href="{{ route('dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-brand-primary transition uppercase tracking-widest px-2">
                        Reset
                    </a>
                    @endif
                </form>
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-[0.15em] font-bold">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">No Request</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Cabang</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Dibuat Oleh</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($requests as $request)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-400 text-sm font-medium">{{ $requests->firstItem() + $loop->index }}</td>
                                <td class="px-6 py-4 font-bold text-brand-primary">
                                    <a href="{{ route('permintaan.show', $request) }}" class="hover:underline underline-offset-4 decoration-2">
                                        {{ $request->no_request }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">{{ $request->tanggal }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-700">{{ $request->cabang->nama }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider
                                        {{ $request->status === 'pending' ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}
                                        {{ $request->status === 'approved' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : '' }}
                                        {{ $request->status === 'rejected' ? 'bg-rose-100 text-rose-700 border border-rose-200' : '' }}
                                        {{ $request->status === 'shipped' ? 'bg-purple-100 text-purple-700 border border-purple-200' : '' }}
                                        {{ $request->status === 'received_complete' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : '' }}
                                        {{ $request->status === 'received_partial' ? 'bg-orange-100 text-orange-700 border border-orange-200' : '' }}
                                        {{ $request->status === 'received' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600' }}">
                                        {{ str_replace('_', ' ', ucfirst($request->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-[11px] font-bold uppercase tracking-tight">{{ $request->user->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        @if(in_array($request->status, ['approved', 'shipped', 'received', 'received_complete', 'received_partial']))
                                            <a href="{{ route('permintaan.print.request', $request) }}" target="_blank" class="p-2 text-slate-400 hover:text-brand-primary hover:bg-slate-50 rounded-lg transition-all" title="Cetak Label">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-slate-400 font-medium">No requests found yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages())
                <div class="px-6 py-6 border-t border-slate-100 bg-slate-50/30">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
