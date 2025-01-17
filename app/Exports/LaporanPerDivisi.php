<?php

namespace App\Exports;

use App\Models\TransaksiHarian;
use Maatwebsite\Excel\Concerns\{FromView};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\View\View;

class LaporanPerDivisi implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $transaksi_harian;
    protected $saldo_kredit;
    protected $saldo_debit;

    public function __construct($transaksi_harian, $saldo_kredit, $saldo_debit)
    {
        $this->transaksi_harian = $transaksi_harian;
        $this->saldo_kredit = $saldo_kredit;
        $this->saldo_debit = $saldo_debit;
    }

    public function collection()
    {
        return $this->transaksi_harian;
    }

    public function view(): View
    {
        return view('excell.divisi', [
            'transaksi_harian' => $this->transaksi_harian,
            'saldo_kredit' => $this->saldo_kredit,
            'saldo_debit' => $this->saldo_debit
        ]);
    }
}
