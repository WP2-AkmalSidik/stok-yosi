<?php

namespace App\Http\Controllers;

use App\Models\Kain;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $fabrics = Kain::with('transaksis')->get();

        $totalAvailableStock = $fabrics->sum(function ($fabric) {
            return $fabric->stok * $fabric->panjang_per_roll -
                $fabric->transaksis()->where('jenis_transaksi', 'keluar')->sum('jumlah');
        });

        return view('landing-page', [
            'fabrics' => $fabrics,
            'totalAvailableStock' => $totalAvailableStock
        ]);
    }
}
