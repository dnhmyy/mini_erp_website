<div id="table-container">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 font-semibold">No</th>
                    <th class="px-6 py-3 font-semibold">No Request</th>
                    <th class="px-6 py-3 font-semibold">Tanggal</th>
                    <th class="px-6 py-3 font-semibold">Cabang</th>
                    <th class="px-6 py-3 font-semibold">Status</th>
                    <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($requests as $request)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-slate-600">
                            {{ $requests->firstItem() + $loop->index }}
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">
                            {{ $request->no_request }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $request->tanggal->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $request->cabang->nama ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold 
                                {{ $request->status === 'pending' ? 'bg-amber-100 text-amber-700 font-bold' : '' }}
                                {{ $request->status === 'approved' ? 'bg-blue-100 text-blue-700 font-bold' : '' }}
                                {{ $request->status === 'shipped' ? 'bg-purple-100 text-purple-700 font-bold' : '' }}
                                {{ $request->status === 'received_complete' ? 'bg-green-100 text-green-700 font-bold' : '' }}
                                {{ $request->status === 'received_partial' ? 'bg-orange-100 text-orange-700 font-bold' : '' }}
                                {{ $request->status === 'received' ? 'bg-green-100 text-green-700 font-bold' : '' }}
                                {{ $request->status === 'rejected' ? 'bg-red-100 text-red-700 font-bold' : '' }}">
                                {{ str_replace('_', ' ', $request->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('permintaan.show', $request) }}" class="inline-flex items-center px-4 py-1.5 bg-slate-100 text-slate-700 hover:bg-brand-primary hover:text-white rounded-lg transition-all text-xs font-bold uppercase tracking-wider">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500 italic">
                            Belum ada permintaan barang.
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
