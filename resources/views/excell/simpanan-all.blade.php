<table>
    <thead>
        <tr>
            <th>Tgl</th>
            <th>Keterangan</th>
            <th>Pokok</th>
            <th>Wajib</th>
            <th>Sukarela</th>
            <th>Kredit</th>
            <th>Saldo</th>
        </tr>
    </thead>
    @if (!empty($transaksi_harian))
    <tbody>
            @php
                $saldo = 0;
                $i = 0;
            @endphp
            @php
                $saldo = $sum_pokok + $sum_wajib + $sum_sukarela - $sum_kredit_simpanan;
            @endphp
            <tr>
                <td></td>
                <td><strong>Saldo Mutasi</strong></td>
                <td>{{ App\Helpers\Money::stringToRupiah($sum_pokok) }}</td>
                <td>{{ App\Helpers\Money::stringToRupiah($sum_wajib) }}</td>
                <td>{{ App\Helpers\Money::stringToRupiah($sum_sukarela) }}</td>
                <td>{{ App\Helpers\Money::stringToRupiah($sum_kredit_simpanan) }}</td>
                <td>{{ App\Helpers\Money::stringToRupiah($saldo) }}</td>
            </tr>
            @foreach ($transaksi_harian as $row)
                @php
                    $saldo += $row->sumPokok->sum('nominal') + $row->sumWajib->sum('nominal') + $row->sumSukarela->sum('nominal') - $row->sumKredit->sum('nominal');
                @endphp
                <tr>
                    <td>{{ App\Helpers\Tanggal::tanggal_id($row->tgl) }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>{{ App\Helpers\Money::stringToRupiah($row->sumPokok->sum('nominal')) }}</td>
                    <td>{{ App\Helpers\Money::stringToRupiah($row->sumWajib->sum('nominal')) }}</td>
                    <td>{{ App\Helpers\Money::stringToRupiah($row->sumSukarela->sum('nominal')) }}</td>
                    <td>{{ App\Helpers\Money::stringToRupiah($row->sumKredit->sum('nominal')) }}</td>
                    <td>{{ App\Helpers\Money::stringToRupiah($saldo) }}</td>
                </tr>
            @endforeach
    </tbody>
    @endif
</table>
