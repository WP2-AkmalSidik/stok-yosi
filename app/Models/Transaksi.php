<?php

namespace App\Models;

use App\Models\Kain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function kain()
    {
        return $this->belongsTo(Kain::class, 'kain_id');
    }
    protected static function booted()
    {
        static::creating(function ($transaksi) {
            // Pastikan jenis transaksi adalah 'keluar' sebelum disimpan
            if (empty($transaksi->jenis_transaksi)) {
                $transaksi->jenis_transaksi = 'keluar';
            }
        });
    }
    
}
