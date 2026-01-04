<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanKeuanganExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Pembayaran::with(['cucian.pelanggan', 'cucian.layanan'])
            ->where('status_bayar', 'lunas')
            ->whereBetween('tgl_bayar', [$this->startDate, $this->endDate])
            ->orderBy('tgl_bayar', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal Bayar',
            'No. Order',
            'Nama Pelanggan',
            'Layanan',
            'Metode Pembayaran',
            'Jumlah Bayar'
        ];
    }

    /**
     * @param mixed $pembayaran
     * @return array
     */
    public function map($pembayaran): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $pembayaran->tgl_bayar->format('d/m/Y H:i'),
            $pembayaran->cucian->getNoOrder(),
            $pembayaran->cucian->pelanggan->nama ?? '-',
            $pembayaran->cucian->layanan->nama_layanan ?? '-',
            ucfirst($pembayaran->metode_bayar),
            $pembayaran->jumlah_bayar
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Keuangan';
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as header
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4CAF50']
                ],
            ],
        ];
    }
}