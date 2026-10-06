<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Print Kategori</title>
    <style>
        @page { margin: 20mm 15mm 25mm 15mm; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
        h3 { margin: 0 0 6px; }
        .info { margin: 0 0 4px; }
        table.daftar { width: 100%; border-collapse: collapse; margin-top: 12px; }
        table.daftar th, table.daftar td { border: 1px solid #333; padding: 6px; font-size: 11px; }
        table.daftar th { background: #eee; }
        .footer {
            position: fixed;
            bottom: -20mm;
            left: 0;
            right: 0;
            text-align: right;
            font-size: 10px;
            color: #555;
            border-top: 1px solid #999;
            padding-top: 4px;
        }
    </style>
</head>
<body>
    <h3>Print Kategori {{ $data->nama }}</h3>
    <div class="info"><b>Kode:</b> {{ $data->kode }}</div>

    <table class="daftar">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Jenis</th>
                <th>Supplier</th>
                <th>Harga Beli</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->masterItems as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->jenis }}</td>
                <td>{{ $item->supplier }}</td>
                <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Dicetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }} | Sistem Master Kategori Items</div>
</body>
</html>