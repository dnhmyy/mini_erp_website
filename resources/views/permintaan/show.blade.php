<x-app-layout>
    <x-slot name="header">
        Detail Permintaan: {{ $permintaan->no_request }}
    </x-slot>

    <div class="space-y-6 max-w-5xl mx-auto">
        <div class="flex items-center justify-between">
            <a href="{{ (url()->previous() === url()->current() || url()->previous() === route('login')) ? route('permintaan.index', ['kategori' => $permintaan->kategori]) : url()->previous() }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-800 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <div class="flex items-center space-x-3">
                @if($permintaan->status === 'pending' && (auth()->user()->isStaffGudang() || auth()->user()->isSuperUser()))
                    <form action="{{ route('permintaan.approve', $permintaan) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">Setujui</button>
                    </form>
                    <form action="{{ route('permintaan.reject', $permintaan) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition">Tolak</button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-2 space-y-6">
                <!-- Details Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden text-sm">
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                        <h4 class="font-bold text-slate-800">Daftar Barang</h4>
                        <span class="text-xs text-slate-500 italic">* Periksa jumlah dengan teliti</span>
                    </div>

                    @php
                        $isShipping = (in_array($permintaan->status, ['approved', 'received_partial']) && (auth()->user()->isStaffGudang() || auth()->user()->isSuperUser() || auth()->user()->isStaffAdmin()));
                        $isReceiving = ($permintaan->status === 'shipped' && auth()->user()->isBranchLevel());
                    @endphp

                    @if($isShipping || $isReceiving)
                    <form action="{{ $isShipping ? route('permintaan.ship', $permintaan) : route('permintaan.receive', $permintaan) }}" method="POST">
                        @csrf
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                                <tr class="border-b border-slate-100">
                                    <th class="px-6 py-3">Nama Produk</th>
                                    <th class="px-6 py-3 text-center">Request</th>
                                    @if($permintaan->status === 'received_partial' && $isShipping)
                                        <th class="px-6 py-3 text-center text-green-600">Diterima</th>
                                    @endif
                                    @if(in_array($permintaan->status, ['shipped', 'received', 'received_complete', 'received_partial']) || $isShipping)
                                        <th class="px-6 py-3 text-center">Dikirim</th>
                                    @endif
                                    @if((in_array($permintaan->status, ['received_complete', 'received_partial']) || $isReceiving) && !$isShipping)
                                        <th class="px-6 py-3 text-center text-green-600">Diterima</th>
                                        <th class="px-6 py-3 text-center text-red-500">Selisih</th>
                                    @endif
                                    <th class="px-6 py-3">Satuan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($permintaan->details as $idx => $detail)
                                    <tr class="item-row" x-data="{ 
                                        qty_dikirim: {{ $detail->qty_dikirim ?? $detail->qty }}, 
                                        qty_terima: {{ $detail->qty_terima ?? 0 }} 
                                    }">
                                        <td class="px-6 py-4 font-medium text-slate-700">
                                            {{ $detail->produk->nama_produk }}
                                            <input type="hidden" name="items[{{ $idx }}][id]" value="{{ $detail->id }}">
                                        </td>
                                        <td class="px-6 py-4 text-center text-slate-600 font-semibold">{{ $detail->qty }}</td>
                                        
                                        @if($permintaan->status === 'received_partial' && $isShipping)
                                            <td class="px-6 py-4 text-center text-green-600 font-bold">
                                                {{ $detail->qty_terima ?? 0 }}
                                            </td>
                                        @endif

                                        @if($isShipping)
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" name="items[{{ $idx }}][qty_dikirim]" 
                                                    x-model="qty_dikirim"
                                                    class="w-20 text-center border-slate-200 rounded-lg px-2 py-1 text-sm focus:ring-brand-primary focus:border-brand-primary"
                                                    min="0">
                                            </td>
                                        @elseif(in_array($permintaan->status, ['shipped', 'received', 'received_complete', 'received_partial']))
                                            <td class="px-6 py-4 text-center text-slate-600">
                                                {{ $detail->qty_dikirim ?? '-' }}
                                            </td>
                                        @endif

                                        @if($isReceiving)
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" name="items[{{ $idx }}][qty_terima]" 
                                                    x-model="qty_terima"
                                                    class="w-20 text-center border-slate-200 rounded-lg px-2 py-1 text-sm focus:ring-brand-primary focus:border-brand-primary"
                                                    min="0">
                                            </td>
                                            <td class="px-6 py-4 text-center font-bold" 
                                                :class="(qty_terima - {{ $detail->qty_dikirim ?? $detail->qty }}) < 0 ? 'text-red-500' : 'text-slate-400'">
                                                <span x-text="qty_terima - {{ $detail->qty_dikirim ?? $detail->qty }}"></span>
                                            </td>
                                        @elseif(in_array($permintaan->status, ['received_complete', 'received_partial']) && !$isShipping)
                                            <td class="px-6 py-4 text-center text-slate-700 font-bold">
                                                {{ $detail->qty_terima }}
                                            </td>
                                            <td class="px-6 py-4 text-center font-bold {{ ($detail->qty_terima - $detail->qty_dikirim) < 0 ? 'text-red-500' : 'text-slate-400' }}">
                                                {{ ($detail->qty_terima ?? 0) - ($detail->qty_dikirim ?? 0) }}
                                            </td>
                                        @endif

                                        <td class="px-6 py-4 text-xs text-slate-500 italic">{{ $detail->produk->satuan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($isShipping)
                        @php
                            $showWarehouseFields = auth()->user()->isSuperUser() || auth()->user()->isStaffGudang();
                            // Also show for Staff Admin but ONLY if requester is Dapur, Pastry, or Mixing
                            if (auth()->user()->isStaffAdmin() && in_array($permintaan->user->role, ['staff_dapur', 'staff_pastry', 'mixing'])) {
                                $showWarehouseFields = true;
                            }
                        @endphp

                        @if($showWarehouseFields && (!$permintaan->gudang_asal || !$permintaan->gudang_tujuan))
                        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4 bg-purple-50/50 border-t border-slate-100">
                            <div>
                                <label for="gudang_asal" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Gudang Asal</label>
                                <select name="gudang_asal" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs bg-white focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">-- Pilih Gudang Asal --</option>
                                    <option value="GUDANG CENTRAL - GA">GUDANG CENTRAL - GA</option>
                                    <option value="GUDANG CENTRAL - KECIL">GUDANG CENTRAL - KECIL</option>
                                    <option value="GUDANG CENTRAL - ISIAN">GUDANG CENTRAL - ISIAN</option>
                                    <option value="GUDANG CENTRAL - PREMIX">GUDANG CENTRAL - PREMIX</option>
                                </select>
                            </div>
                            <div>
                                <label for="gudang_tujuan" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Gudang Tujuan</label>
                                <select name="gudang_tujuan" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs bg-white focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">-- Pilih Gudang Tujuan --</option>
                                    <option value="Central Kitchen">Central Kitchen</option>
                                    <option value="Mixing">Mixing</option>
                                    <option value="Pastry">Pastry</option>
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="px-6 py-4 bg-purple-50 border-t border-slate-100 flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center space-x-2">
                                <label class="text-xs font-bold text-slate-600">PILIH KURIR:</label>
                                <select name="driver" class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-purple-500 bg-white">
                                    <option value="">-- Kurir (Opsional) --</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->nama }}">{{ $driver->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg text-sm font-bold hover:bg-purple-700 shadow-md transition-all active:scale-95">KONFIRMASI PENGIRIMAN</button>
                        </div>
                    </form>
                    @endif

                    @if($isReceiving)
                        <div class="px-6 py-4 bg-green-50 border-t border-slate-100 flex items-center justify-end">
                            <button type="submit" onclick="return confirm('Apakah jumlah yang diinput sudah sesuai?')" class="px-6 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 shadow-md transition-all active:scale-95">KONFIRMASI PENERIMAAN CABANG</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <!-- Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 space-y-4 text-sm">
                    <h4 class="font-bold text-slate-800 border-b border-slate-100 pb-2">Informasi Request</h4>
                    
                    <div class="flex justify-between items-start">
                        <span class="text-slate-500">No Request</span>
                        <span class="font-bold text-slate-800">{{ $permintaan->no_request }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-slate-500">Status</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold
                            {{ $permintaan->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $permintaan->status === 'approved' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $permintaan->status === 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                            {{ $permintaan->status === 'received_complete' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $permintaan->status === 'received_partial' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $permintaan->status === 'received' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $permintaan->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ str_replace('_', ' ', $permintaan->status) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-start pt-2 border-t border-slate-50">
                        <span class="text-slate-50">Kategori</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold bg-slate-100 text-slate-700">
                            {{ $permintaan->kategori === 'BB' ? 'Bahan Baku' : ($permintaan->kategori === 'ISIAN' ? 'Isian' : 'General Affair') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-slate-500">Cabang</span>
                        <span class="text-slate-800 font-medium">{{ $permintaan->cabang->nama ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-slate-500">Tanggal</span>
                        <span class="text-slate-800">{{ $permintaan->tanggal->format('d M Y') }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-slate-500">Dibuat Oleh</span>
                        <span class="text-slate-800">{{ $permintaan->user->name }}</span>
                    </div>
                </div>

                <!-- Print Section (Restricted for Branch Admin) -->
                @if(!auth()->user()->isBranchLevel() && in_array($permintaan->status, ['approved', 'shipped', 'received', 'received_complete', 'received_partial']))
                    <div class="bg-brand-primary/5 rounded-xl border border-brand-primary/10 p-4 space-y-3">
                        <h5 class="text-xs font-bold text-brand-primary uppercase tracking-wider">Cetak Dokumen</h5>
                        <div class="space-y-2">
                            <a href="{{ route('permintaan.print.request', $permintaan) }}" target="_blank" class="w-full flex items-center justify-between px-3 py-2 bg-white rounded-lg border border-slate-200 text-slate-700 hover:border-brand-primary transition group text-sm">
                                <span>Surat Permintaan</span>
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            </a>
                            
                            @if(in_array($permintaan->status, ['shipped', 'received', 'received_complete', 'received_partial']))
                                <a href="{{ route('permintaan.print.delivery', $permintaan) }}" target="_blank" class="w-full flex items-center justify-between px-3 py-2 bg-white rounded-lg border border-slate-200 text-slate-700 hover:border-brand-primary transition group text-sm">
                                    <span>Surat Jalan</span>
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                            @endif

                            @if(in_array($permintaan->status, ['received', 'received_complete', 'received_partial']))
                                <a href="{{ route('permintaan.print.receipt', $permintaan) }}" target="_blank" class="w-full flex items-center justify-between px-3 py-2 bg-white rounded-lg border border-slate-200 text-slate-700 hover:border-brand-primary transition group text-sm">
                                    <span>Surat Terima</span>
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
