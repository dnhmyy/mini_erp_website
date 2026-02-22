<!DOCTYPE html>
<html>
<head>
    <title>Surat Jalan - {{ $permintaan->no_request }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 0; padding: 0; }
        .header { text-align: left; border-bottom: 2px solid #00271b; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
        .logo { position: absolute; top: 0; right: 0; height: 60px; }
        .header h1 { margin: 0; color: #00271b; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10px; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .info-table td.label { width: 120px; font-weight: bold; }
        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .content-table th { background-color: #f1f5f9; border: 1px solid #cbd5e1; padding: 8px; font-size: 11px; text-align: left; }
        .content-table td { border: 1px solid #cbd5e1; padding: 8px; font-size: 11px; }
        .footer { width: 100%; margin-top: 50px; }
        .footer td { text-align: center; width: 25%; vertical-align: bottom; }
        .signature { border-top: 1px solid #333; width: 100px; margin: 0 auto; padding-top: 5px; margin-top: 40px; }
    </style>
</head>
<body>
   <!-- <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo">
        <h1>ROTI KEBANGGAAN</h1>
        <p>Internal Goods Request System</p>
    </div> -->

    @php
        $role = $permintaan->user->role;
        $kategori = $permintaan->kategori;
        $title = 'SURAT JALAN (PENGIRIMAN)';
        
        if ($role === 'staff_admin') {
            if ($kategori === 'BB') $title = 'PERMINTAAN BARANG';
            elseif ($kategori === 'ISIAN') $title = 'PERMINTAAN BAHAN - ISIAN';
            elseif ($kategori === 'GA') $title = 'PERMINTAAN PERALATAN';
        } elseif ($role === 'staff_produksi') {
            if ($kategori === 'BB') $title = 'PERMINTAAN BAHAN BAKU';
            elseif ($kategori === 'ISIAN') $title = 'PERMINTAAN BAHAN - ISIAN';
            elseif ($kategori === 'GA') $title = 'PERMINTAAN PERALATAN';
        }

        $isSpecial = in_array($role, ['staff_dapur', 'staff_pastry', 'mixing']);
    @endphp

    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 16px;">{{ strtoupper($title) }}</h2>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">TANGGAL PERMINTAAN</td>
            <td>: {{ strtoupper(\Carbon\Carbon::parse($permintaan->tanggal)->translatedFormat('d F Y')) }}</td>
            <td class="label">NAMA PEMOHON</td>
            <td style="font-weight: bold;">: {{ strtoupper($permintaan->user->name ?? '-') }}</td>
        </tr>
        <tr>
            <td class="label">NO. SURAT JALAN</td>
            <td>: SJ-{{ $permintaan->no_request }}</td>
            <td class="label">TGL. KIRIM</td>
            <td>: {{ strtoupper(now()->translatedFormat('d F Y')) }}</td>
        </tr>

        @if($isSpecial)
        <tr>
            <td class="label">GUDANG ASAL</td>
            <td style="font-weight: bold;">: {{ strtoupper($permintaan->gudang_asal ?? '-') }}</td>
            <td class="label">GUDANG TUJUAN</td>
            <td style="font-weight: bold;">: {{ strtoupper($permintaan->gudang_tujuan ?? '-') }}</td>
        </tr>
        @else
        <tr>
            <td class="label">PENGIRIM</td>
            <td style="font-weight: bold;">: {{ ($permintaan->kategori === 'BB' || $permintaan->kategori === 'ISIAN') ? 'CENTRAL' : 'GA' }}</td>
            <td class="label">TUJUAN (CABANG)</td>
            <td style="font-weight: bold;">: {{ strtoupper($permintaan->cabang->nama ?? '-') }}</td>
        </tr>
        <tr>
            <td class="label">ALAMAT TUJUAN</td>
            <td colspan="3">: {{ strtoupper($permintaan->cabang->alamat ?? '-') }}</td>
        </tr>
        @endif

        @if($permintaan->driver)
        <tr>
            <td class="label">NAMA DRIVER</td>
            <td colspan="3">: {{ strtoupper($permintaan->driver) }}</td>
        </tr>
        @endif
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th>Kode Produk</th>
                <th>Nama Barang</th>
                <th style="width: 80px; text-align: center;">Jumlah</th>
                <th style="width: 80px;">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaan->details as $index => $detail)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $detail->produk->kode_produk }}</td>
                    <td>{{ $detail->produk->nama_produk }}</td>
                    <td style="text-align: center;">{{ $detail->qty }}</td>
                    <td>{{ $detail->produk->satuan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td>
                Pengirim,
                <div class="signature">Gudang</div>
            </td>
            <td>
                Sopir,
                <div class="signature"></div>
            </td>
            <td>
                Keamanan,
                <div class="signature"></div>
            </td>
            <td>
                Penerima,
                <div class="signature">Cabang</div>
            </td>
        </tr>
    </table>
</body>
</html>
