<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan - {{ $periodeText }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            color: #333;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 5px;
        }
        .summary {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            color: #4CAF50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background-color: #4CAF50;
            color: white;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>WASHWES LAUNDRY</h2>
        <h3>LAPORAN KEUANGAN</h3>
        <p>Periode: {{ $periodeText }}</p>
    </div>

    <div class="summary">
        <h3>RINGKASAN</h3>
        <table style="border: none;">
            <tr>
                <td style="border: none; width: 50%;"><strong>Total Penghasilan:</strong></td>
                <td style="border: none; text-align: right;"><strong style="color: #4CAF50; font-size: 16px;">Rp {{ number_format($totalPenghasilan, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Total Transaksi:</strong></td>
                <td style="border: none; text-align: right;">{{ $pembayaran->count() }} transaksi</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Rata-rata per Transaksi:</strong></td>
                <td style="border: none; text-align: right;">Rp {{ $pembayaran->count() > 0 ? number_format($totalPenghasilan / $pembayaran->count(), 0, ',', '.') : 0 }}</td>
            </tr>
        </table>
    </div>

    <h3>BREAKDOWN PER LAYANAN</h3>
    <table>
        <thead>
            <tr>
                <th>Layanan</th>
                <th class="text-center">Jumlah Transaksi</th>
                <th class="text-right">Total Penghasilan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($breakdownLayanan as $item)
            <tr>
                <td>{{ $item['layanan'] }}</td>
                <td class="text-center">{{ $item['jumlah'] }}</td>
                <td class="text-right">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td><strong>TOTAL</strong></td>
                <td class="text-center"><strong>{{ $pembayaran->count() }}</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPenghasilan, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <h3>DETAIL TRANSAKSI</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 15%;">No. Order</th>
                <th style="width: 25%;">Pelanggan</th>
                <th style="width: 20%;">Layanan</th>
                <th style="width: 20%;" class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembayaran as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->tgl_bayar->format('d/m/Y H:i') }}</td>
                <td>{{ $item->cucian->getNoOrder() }}</td>
                <td>{{ $item->cucian->pelanggan->nama ?? '-' }}</td>
                <td>{{ $item->cucian->layanan->nama_layanan ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->isoFormat('D MMMM YYYY, HH:mm') }}</p>
        <p>&copy; {{ now()->year }} Washwes Laundry Management System</p>
    </div>
</body>
</html>