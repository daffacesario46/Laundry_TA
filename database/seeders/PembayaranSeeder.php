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
        
        // Generate data tambahan untuk Januari - November 2025
        $this->generateDataTahunan();
    }
    
    private function generateDataTahunan()
    {
        $this->command->info('');
        $this->command->info('🔄 Generating data Cucian + Pembayaran untuk Januari - November 2025...');
        
        $pelangganIds = range(1, 10); // ID pelanggan 1-10
        $layananData = [
            ['id' => 1, 'jenis' => 'kiloan', 'harga_per_kg' => 5000, 'durasi' => 2],
            ['id' => 2, 'jenis' => 'kiloan', 'harga_per_kg' => 7000, 'durasi' => 3],
            ['id' => 3, 'jenis' => 'kiloan', 'harga_per_kg' => 10000, 'durasi' => 1],
            ['id' => 4, 'jenis' => 'kiloan', 'harga_per_kg' => 4000, 'durasi' => 1],
            ['id' => 5, 'jenis' => 'satuan', 'harga_per_item' => 18000, 'durasi' => 3],
            ['id' => 6, 'jenis' => 'satuan', 'harga_per_item' => 20000, 'durasi' => 4],
        ];
        
        $jenisOrder = ['online', 'offline'];
        $jenisAmbil = ['ambil_sendiri', 'diantar'];
        $metodeBayar = ['cash', 'transfer']; // Sesuaikan dengan enum di database
        
        $totalGenerated = 0;
        $tahun = 2025;
        
        // Generate data untuk bulan Januari - November 2025
        for ($bulan = 1; $bulan <= 11; $bulan++) {
            // Random jumlah transaksi per bulan (20-35 transaksi)
            $jumlahTransaksi = rand(20, 35);
            
            for ($i = 0; $i < $jumlahTransaksi; $i++) {
                // Data random
                $pelangganId = $pelangganIds[array_rand($pelangganIds)];
                $layanan = $layananData[array_rand($layananData)];
                $jnsOrder = $jenisOrder[array_rand($jenisOrder)];
                $jnsAmbil = $jenisAmbil[array_rand($jenisAmbil)];
                $metode = $metodeBayar[array_rand($metodeBayar)];
                
                // Tanggal order random dalam bulan tersebut
                $daysInMonth = Carbon::create($tahun, $bulan, 1)->daysInMonth;
                $hariOrder = rand(1, $daysInMonth);
                $tglOrder = Carbon::create($tahun, $bulan, $hariOrder)
                    ->setTime(rand(8, 18), rand(0, 59), 0);
                
                // Hitung estimasi selesai
                $estimasi = $tglOrder->copy()->addDays($layanan['durasi']);
                $tglSelesai = $estimasi->copy();
                $tglDiambil = $tglSelesai->copy()->addHours(rand(2, 48));
                
                // Generate berat/item dan harga
                if ($layanan['jenis'] == 'kiloan') {
                    $berat = rand(20, 100) / 10; // 2.0 - 10.0 kg
                    $totalHarga = $berat * $layanan['harga_per_kg'];
                    $totalItem = 1;
                    $beratFinal = $berat;
                } else {
                    $item = rand(1, 5);
                    $totalHarga = $item * $layanan['harga_per_item'];
                    $totalItem = $item;
                    $beratFinal = null;
                }
                
                // Insert Cucian
                $cucianId = DB::table('cucian')->insertGetId([
                    'pelanggan_id' => $pelangganId,
                    'layanan_id' => $layanan['id'],
                    'jenis_order' => $jnsOrder,
                    'jenis_ambil' => $jnsAmbil,
                    'tgl_order' => $tglOrder,
                    'estimasi' => $estimasi,
                    'tgl_selesai' => $tglSelesai,
                    'tgl_diambil' => $tglDiambil,
                    'total_item' => $totalItem,
                    'total_berat' => $beratFinal,
                    'total_harga' => $totalHarga,
                    'status_cucian' => 'diambil',
                    'catatan' => null,
                ]);
                
                // Insert Pembayaran (langsung lunas)
                DB::table('pembayaran')->insert([
                    'cucian_id' => $cucianId,
                    'jumlah_bayar' => $totalHarga,
                    'metode_bayar' => $metode,
                    'status_bayar' => 'lunas',
                    'tgl_bayar' => $tglDiambil,
                    'bukti_bayar' => null,
                    'catatan' => null,
                ]);
                
                $totalGenerated++;
            }
            
            $bulanNama = Carbon::create($tahun, $bulan, 1)->isoFormat('MMMM');
            $this->command->info("  ✓ {$bulanNama}: {$jumlahTransaksi} transaksi (cucian + pembayaran)");
        }
        
        $this->command->info('');
        $this->command->info("✅ Berhasil generate {$totalGenerated} data cucian + pembayaran!");
        $this->command->info("📊 Total cucian sekarang: " . DB::table('cucian')->count());
        $this->command->info("💰 Total pembayaran sekarang: " . DB::table('pembayaran')->count());
    }
}