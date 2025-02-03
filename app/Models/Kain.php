<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class Kain extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kain', 'stok', 'panjang_per_roll', 'deskripsi'];
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
    public function getAvailableStockAttribute()
    {
        $stokAwalYard = $this->stok * $this->panjang_per_roll;
        $stokKeluar = $this->transaksis()->where('jenis_transaksi', 'keluar')->sum('jumlah');
        return $stokAwalYard - $stokKeluar;
    }
}
