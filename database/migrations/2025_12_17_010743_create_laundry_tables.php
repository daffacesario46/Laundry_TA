<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table: users
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('users_id');
            $table->enum('role', ['admin', 'kurir', 'staff', 'pelanggan']);
            $table->string('email', 255)->unique();
            $table->string('nama', 255);
            $table->string('password', 255);
            $table->string('no_telp', 20)->nullable();
            $table->string('no_wa', 20)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('foto', 255)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
        });

        // Table: pelanggan
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->increments('pelanggan_id');
            $table->unsignedBigInteger('users_id')->nullable()
            ;$table->enum('kategori_pelanggan', ['online', 'offline'])->default('offline');
            $table->string('nama', 100);
            $table->string('no_telp', 20)->nullable();
            $table->string('no_wa', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto', 255)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('users_id', 'fk_pelanggan_users')
                  ->references('users_id')->on('users')
                  ->onDelete('set null')->onUpdate('cascade');
        });

        // Table: layanan
        Schema::create('layanan', function (Blueprint $table) {
            $table->increments('layanan_id');
            $table->string('nama_layanan', 100);
            $table->enum('jenis_cucian', ['kiloan', 'satuan']);
            $table->text('deskripsi')->nullable();
            $table->integer('durasi_hari')->default(3);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
        });

        // Table: list_harga
        Schema::create('list_harga', function (Blueprint $table) {
            $table->bigIncrements('list_harga_id');
            $table->string('nama_item', 255);
            $table->double('harga_satuan');
            $table->double('harga_kiloan')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
        });

        // Table: cucian
        Schema::create('cucian', function (Blueprint $table) {
            $table->bigIncrements('cucian_id');
            $table->unsignedInteger('pelanggan_id');
            $table->unsignedInteger('layanan_id')->nullable();
            $table->enum('jenis_order', ['online', 'offline']);
            $table->enum('jenis_ambil', ['diantar', 'ambil_sendiri']);
            $table->dateTime('tgl_order')->useCurrent();
            $table->dateTime('estimasi')->nullable();
            $table->dateTime('tgl_selesai')->nullable();
            $table->dateTime('tgl_diambil')->nullable();
            $table->integer('total_item')->default(0);
            $table->double('total_berat')->nullable();
            $table->double('total_harga')->nullable();
            $table->enum('status_cucian', ['menunggu', 'diproses', 'selesai', 'diambil']);
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('pelanggan_id', 'fk_cucian_pelanggan')
                  ->references('pelanggan_id')->on('pelanggan');
            $table->foreign('layanan_id', 'fk_cucian_layanan')
                  ->references('layanan_id')->on('layanan')
                  ->onDelete('set null');
        });

        // Table: cucian_detail
        Schema::create('cucian_detail', function (Blueprint $table) {
            $table->bigIncrements('cucian_detail_id');
            $table->unsignedBigInteger('cucian_id');
            $table->unsignedBigInteger('list_harga_id');
            $table->integer('jumlah')->default(1);
            $table->double('berat_kg')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('cucian_id', 'fk_cd_cucian')
                  ->references('cucian_id')->on('cucian')
                  ->onDelete('cascade');
            $table->foreign('list_harga_id', 'fk_cd_list_harga')
                  ->references('list_harga_id')->on('list_harga');
        });

        // Table: pembayaran
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->bigIncrements('pembayaran_id');
            $table->unsignedBigInteger('cucian_id');
            $table->enum('metode_bayar', ['transfer', 'cash']);
            $table->enum('status_bayar', ['belum', 'lunas'])->default('belum');
            $table->double('jumlah_bayar');
            $table->dateTime('tgl_bayar')->nullable();
            $table->string('bukti_bayar', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('cucian_id')->references('cucian_id')->on('cucian')
                  ->onDelete('cascade');
        });

        // Table: penjemputan
        Schema::create('penjemputan', function (Blueprint $table) {
            $table->bigIncrements('penjemputan_id');
            $table->unsignedBigInteger('cucian_id');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->text('alamat_jemput');
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->dateTime('tgl_order')->nullable()->useCurrent();
            $table->string('foto', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('cucian_id')->references('cucian_id')->on('cucian')
                  ->onDelete('cascade');
            $table->foreign('staff_id', 'fk_penjemputan_staff')
                  ->references('users_id')->on('users')
                  ->onDelete('set null')->onUpdate('cascade');
        });

        // Table: pengantaran
        Schema::create('pengantaran', function (Blueprint $table) {
            $table->bigIncrements('pengantaran_id');
            $table->unsignedBigInteger('cucian_id');
            $table->unsignedBigInteger('kurir_id')->nullable();
            $table->text('alamat_antar');
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->dateTime('tgl_berangkat')->nullable();
            $table->string('foto', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('cucian_id')->references('cucian_id')->on('cucian')
                  ->onDelete('cascade');
            $table->foreign('kurir_id', 'fk_pengantaran_kurir')
                  ->references('users_id')->on('users')
                  ->onDelete('set null')->onUpdate('cascade');
        });

        // Table: stok_bahan
        Schema::create('stok_bahan', function (Blueprint $table) {
            $table->bigIncrements('stok_bahan_id');
            $table->string('jenis_bahan', 100)->nullable();
            $table->string('merk', 255)->nullable();
            $table->double('stok_tersedia')->nullable();
            $table->string('satuan', 30)->nullable();
            $table->double('harga_beli')->nullable();
            $table->double('stok_minimum')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
        });

        // Table: pembelian
        Schema::create('pembelian', function (Blueprint $table) {
            $table->bigIncrements('pembelian_id');
            $table->string('kode_beli', 20);
            $table->timestamp('wkt_beli')->useCurrent();
            $table->date('tanggal_beli')->nullable();
            $table->time('jam_beli')->nullable();
            $table->string('jenis_bahan', 100)->nullable();
            $table->string('merk', 255)->nullable();
            $table->double('jumlah_beli')->nullable();
            $table->double('total_harga')->nullable();
            $table->string('bukti', 255)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
        });

        // Table: pemakaian
        Schema::create('pemakaian', function (Blueprint $table) {
            $table->bigIncrements('pemakaian_id');
            $table->unsignedBigInteger('pembelian_id')->nullable();
            $table->unsignedBigInteger('stok_bahan_id')->nullable();
            $table->double('jumlah_terpakai')->nullable();
            $table->string('bukti', 255)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('pembelian_id')->references('pembelian_id')->on('pembelian')
                  ->onDelete('set null');
            $table->foreign('stok_bahan_id')->references('stok_bahan_id')->on('stok_bahan')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemakaian');
        Schema::dropIfExists('pembelian');
        Schema::dropIfExists('stok_bahan');
        Schema::dropIfExists('pengantaran');
        Schema::dropIfExists('penjemputan');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('cucian_detail');
        Schema::dropIfExists('cucian');
        Schema::dropIfExists('list_harga');
        Schema::dropIfExists('layanan');
        Schema::dropIfExists('pelanggan');
        Schema::dropIfExists('users');
    }
};