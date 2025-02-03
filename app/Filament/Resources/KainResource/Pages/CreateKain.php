<?php

namespace App\Filament\Resources\KainResource\Pages;

use App\Models\Transaksi;
use App\Filament\Resources\KainResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKain extends CreateRecord
{
    protected static string $resource = KainResource::class;

    protected function afterCreate(): void
    {
        $kain = $this->record; // Data kain setelah dibuat

        // Hitung stok masuk dalam yard
        $stokMasuk = $kain->stok * $kain->panjang_per_roll;

        // Buat transaksi masuk hanya jika stok awal lebih dari 0
        if ($stokMasuk > 0) {
            Transaksi::create([
                'kain_id' => $kain->id,
                'jenis_transaksi' => 'masuk',
                'jumlah' => $stokMasuk,
                'keterangan' => 'Stok awal saat menambahkan kain.',
            ]);
        }
    }

}
