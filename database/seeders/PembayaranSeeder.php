<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $pembayaran = [
            // Pembayaran untuk cucian yang sudah diambil (status: lunas)
            [
                'cucian_id' => 1,
                'metode_bayar' => 'transfer',
                'status_bayar' => 'lunas',
                'jumlah_bayar' => 35000,
                'tgl_bayar' => Carbon::now()->subDays(6),
                'bukti_bayar' => null,
                'catatan' => 'Pembayaran via transfer BCA',
            ],
            [
                'cucian_id' => 2,
                'metode_bayar' => 'cash',
                'status_bayar' => 'lunas',
                'jumlah_bayar' => 35000,
                'tgl_bayar' => Carbon::now()->subDays(7),
                'bukti_bayar' => null,
                'catatan' => null,
            ],
            [
                'cucian_id' => 6,
                'metode_bayar' => 'cash',
                'status_bayar' => 'lunas',
                'jumlah_bayar' => 42000,
                'tgl_bayar' => Carbon::now()->subDays(8),
                'bukti_bayar' => null,
                'catatan' => null,
            ],
            [
                'cucian_id' => 7,
                'metode_bayar' => 'cash',
                'status_bayar' => 'lunas',
                'jumlah_bayar' => 12000,
                'tgl_bayar' => Carbon::now()->subDays(4),
                'bukti_bayar' => null,
                'catatan' => null,
            ],
            
            // Pembayaran untuk cucian yang sudah selesai tapi belum diambil (status: lunas)
            [
                'cucian_id' => 3,
                'metode_bayar' => 'transfer',
                'status_bayar' => 'lunas',
                'jumlah_bayar' => 54000,
                'tgl_bayar' => Carbon::now()->subDays(2),
                'bukti_bayar' => null,
                'catatan' => 'Transfer via Mandiri',
            ],
            [
                'cucian_id' => 8,
                'metode_bayar' => 'cash',
                'status_bayar' => 'lunas',
                'jumlah_bayar' => 57000,
                'tgl_bayar' => Carbon::now()->subDays(1),
                'bukti_bayar' => null,
                'catatan' => null,
            ],
            
            // Pembayaran untuk cucian yang sedang diproses (status: belum)
            [
                'cucian_id' => 4,
                'metode_bayar' => 'transfer',
                'status_bayar' => 'belum',
                'jumlah_bayar' => 20000,
                'tgl_bayar' => null,
                'bukti_bayar' => null,
                'catatan' => 'Menunggu konfirmasi transfer',
            ],
            [
                'cucian_id' => 5,
                'metode_bayar' => 'transfer',
                'status_bayar' => 'belum',
                'jumlah_bayar' => 40000,
                'tgl_bayar' => null,
                'bukti_bayar' => null,
                'catatan' => null,
            ],
            [
                'cucian_id' => 9,
                'metode_bayar' => 'cash',
                'status_bayar' => 'belum',
                'jumlah_bayar' => 37500,
                'tgl_bayar' => null,
                'bukti_bayar' => null,
                'catatan' => 'Bayar saat ambil',
            ],
            
            // Pembayaran untuk cucian baru masuk (status: belum)
            [
                'cucian_id' => 10,
                'metode_bayar' => 'cash',
                'status_bayar' => 'belum',
                'jumlah_bayar' => 17500,
                'tgl_bayar' => null,
                'bukti_bayar' => null,
                'catatan' => 'Bayar saat ambil',
            ],
        ];

        foreach ($pembayaran as $data) {
            DB::table('pembayaran')->insert($data);
        }

        $this->command->info('✓ Pembayaran seeder completed: 10 pembayaran created (6 lunas, 4 belum)');
    }
}