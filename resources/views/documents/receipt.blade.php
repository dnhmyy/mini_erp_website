<!DOCTYPE html>
<html>
<head>
    <title>Surat Terima Barang - {{ $permintaan->no_request }}</title>
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
        .footer td { text-align: center; width: 33%; vertical-align: bottom; }
        .signature { border-top: 1px solid #333; width: 120px; margin: 0 auto; padding-top: 5px; margin-top: 40px; }
    </style>
</head>
<body>
   <!-- <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo">
        <h1>ROTI KEBANGGAAN</h1>
        <p>Internal Goods Request System</p>
    </div> -->

    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 16px;">SURAT TERIMA BARANG</h2>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">NO. REQUEST</td>
            <td>: {{ $permintaan->no_request }}</td>
            <td class="label">PENERIMA</td>
            <td>: {{ strtoupper($permintaan->user->name) }}</td>
        </tr>
        <tr>
            <td class="label">TGL. TERIMA</td>
            <td>: {{ strtoupper(now()->translatedFormat('d F Y')) }}</td>
            <td class="label">SUPPLIER</td>
            <td style="font-weight: bold;">: BAKERY SOLUTION</td>
        </tr>
        @php
            $isSpecial = in_array($permintaan->user->role, ['staff_dapur', 'staff_pastry', 'mixing']);
        @endphp
        <tr>
            <td class="label">CABANG</td>
            <td>: {{ strtoupper($permintaan->cabang->nama ?? '-') }}</td>
            <td class="label">
                @if($isSpecial) GUDANG ASAL @else TUJUAN @endif
            </td>
            <td style="font-weight: bold;">: 
                @if($isSpecial)
                    {{ strtoupper($permintaan->gudang_asal ?? '-') }}
                @else
                    {{ ($permintaan->kategori === 'BB' || $permintaan->kategori === 'ISIAN') ? 'CENTRAL' : 'GA' }}
                @endif
            </td>
        </tr>
        @if($isSpecial)
        <tr>
            <td class="label">GUDANG TUJUAN</td>
            <td style="font-weight: bold;" colspan="3">: {{ strtoupper($permintaan->gudang_tujuan ?? '-') }}</td>
        </tr>
        @endif
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th>Kode Produk</th>
                <th>Nama Barang</th>
                <th style="width: 80px; text-align: center;">Jumlah Dibeli</th>
                <th style="width: 80px; text-align: center;">Jumlah Diterima</th>
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
                    <td style="text-align: center;">{{ $detail->qty }}</td>
                    <td>{{ $detail->produk->satuan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td>
                Diserahkan Oleh,
                <div class="signature">Sopir / Kurir</div>
            </td>
            <td>
                Diterima Oleh,
                <div class="signature">{{ auth()->user()->name }}</div>
            </td>
            <td>
                Diketahui Oleh,
                <div class="signature">Adm. Cabang</div>
            </td>
        </tr>
    </table>
</body>
</html>
