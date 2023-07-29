@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="float-left">
                <h4 class="page-title">Dashboard </h4>
                <small class="text-danger">Periode : {{ $periode->name }}</small>
            </div>
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Kopkar</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <small class="text-danger">Tahun Buku : {{ date('d-m-Y', strtotime($periode->open_date)) }} - {{ date('d-m-Y', strtotime($periode->close_date)) }}</small>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-8">
        <div class="row">
            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card-box tilebox-one">
                    <h6 class="text-muted text-uppercase mb-3">Pinjaman Bulan {{ date('F') }} {{ date('Y') }}</h6>
                    <table class="w-100">
                        <tr style="border-bottom: 1px solid gray;">
                            <td style="width: 100px">{{ number_format($debet_pinjaman) }}</td>
                            <td>Total Uang dipinjam</td>
                        </tr>
                        <tr style="border-bottom: 1px solid gray;">
                            <td>{{ number_format($jt_pelunasan) }}</td>
                            <td>Jatuh Tempo Pelunasan </td>
                        </tr>
                        <tr style="border-bottom: 1px solid gray;">
                            <td>{{ number_format($jt_pelunasan_dibayar) }}</td>
                            <td>Pelunasan Dibayar</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card-box tilebox-one">
                    <h6 class="text-muted text-uppercase mb-3">Simpanan Bulan {{ date('F') }} {{ date('Y') }}</h6>
                    <table class="w-100">
                        <tr style="border-bottom: 1px solid gray;">
                            <td style="width: 100px">{{ number_format($sum_pokok) }}</td>
                            <td>Simpanan Pokok</td>
                        </tr>
                        <tr style="border-bottom: 1px solid gray;">
                            <td>{{ number_format($sum_wajib) }}</td>
                            <td>Simpanan Wajib</td>
                        </tr>
                        <tr style="border-bottom: 1px solid gray;">
                            <td>{{ number_format($sum_sukarela) }}</td>
                            <td>Simpanan Sukarela</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card-box tilebox-one">
                    <h6 class="text-muted text-uppercase mb-3">Data Anggota</h6>
                    <table class="w-100">
                        <tr style="border-bottom: 1px solid gray;">
                            <td style="width: 100px">{{ number_format($anggota_aktif + $anggota_disable) }}</td>
                            <td>Total Anggota</td>
                        </tr>
                        <tr style="border-bottom: 1px solid gray;">
                            <td>{{ number_format($anggota_aktif) }}</td>
                            <td>Anggota Aktif</td>
                        </tr>
                        <tr style="border-bottom: 1px solid gray;">
                            <td>{{ number_format($anggota_disable) }}</td>
                            <td>Anggota non Aktif</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card-box tilebox-one">
                    <h6 class="text-muted text-uppercase mb-3">Data Peminjam</h6>
                    <table class="w-100">
                        <tr style="border-bottom: 1px solid gray;">
                            <td style="width: 100px">{{ $transaksi_pinjam->count() }}</td>
                            <td>Total Peminjam</td>
                        </tr>
                        <tr style="border-bottom: 1px solid gray;">
                            <td>{{ $transaksi_pinjam->where('status',1)->count() }}</td>
                            <td>Sudah Lunas</td>
                        </tr>
                        <tr style="border-bottom: 1px solid gray;">
                            <td>{{ $transaksi_pinjam->where('status',0)->count() }}</td>
                            <td>Belum Lunas</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card p-3 shadow">
            <div class="row">
                <div class="col-12">
                    <div class="card-box tilebox-one">
                        <h6 class="text-muted text-uppercase mb-3">Kas Bulan {{ date('F') }} {{ date('Y') }}</h6>
                        <table class="w-100">
                        <tr style="border-bottom: 1px solid gray;">
                            <td style="width: 100px">{{ number_format($kas_debit) }}</td>
                            <td>Debet</td>
                        </tr>
                        <tr style="border-bottom: 1px solid gray;">
                            <td>{{ number_format($kas_kredit) }}</td>
                            <td>Kredit</td>
                        </tr>
                        <tr style="border-bottom: 1px solid gray;">
                            <td>{{ number_format($kas_debit - $kas_kredit) }}</td>
                            <td>Saldo</td>
                        </tr>
                    </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box tilebox-one">
                        <h6 class="text-muted text-uppercase mb-3">Bank Bulan {{ date('F') }} {{ date('Y') }}</h6>
                        <table class="w-100">
                        <tr style="border-bottom: 1px solid gray;">
                            <td style="width: 100px">{{ number_format($bank_debit) }}</td>
                            <td>Debet</td>
                        </tr>
                        <tr style="border-bottom: 1px solid gray;">
                            <td>{{ number_format($bank_kredit) }}</td>
                            <td>Kredit</td>
                        </tr>
                        <tr style="border-bottom: 1px solid gray;">
                            <td>{{ number_format($bank_debit - $bank_kredit) }}</td>
                            <td>Saldo</td>
                        </tr>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <div class="row">
    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box tilebox-one">
            <i class="fi-layers float-right"></i>
            <h6 class="text-muted text-uppercase mb-3">Saldo Pokok</h6>
            <h6 class="mb-3">{{ number_format($sum_pokok) }}</h6>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box tilebox-one">
            <i class="fi-layers float-right"></i>
            <h6 class="text-muted text-uppercase mb-3">Saldo Wajib</h6>
            <h6 class="mb-3"><span>{{ number_format($sum_wajib) }}</span></h6>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box tilebox-one">
            <i class="fi-layers float-right"></i>
            <h6 class="text-muted text-uppercase mb-3">Saldo Sukarela</h6>
            <h6 class="mb-3"><span>{{ number_format($sum_sukarela) }}</span></h6>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box tilebox-one">
            <i class="fi-layers float-right"></i>
            <h6 class="text-muted text-uppercase mb-3">Kredit Simpanan</h6>
            <h6 class="mb-3"><span>{{ number_format($kredit_simpanan) }}</span></h6>
        </div>
    </div>
</div> --}}
{{-- <div class="row">
    <div class="col-xl-6">
        <div class="card-box">
            <h4 class="header-title">TRANSAKSI Periode : {{ $periode->name }}</h4>
            <small class="text-danger">Tahun Buku : {{ date('d-m-Y', strtotime($periode->open_date)) }} - {{ date('d-m-Y', strtotime($periode->close_date)) }}</small>
            <div class="row">
                <div class="col-sm-6">
                    <div class="text-center mt-3">
                        <h6 class="font-normal text-muted font-14">Total Pinjaman</h6>
                        <h6 class="font-18"><i class="mdi mdi-arrow-down-bold-hexagon-outline text-success"></i> <span class="text-dark">{{ number_format($saldo_kredit) }}</span> </h6>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="text-center mt-3">
                        <h6 class="font-normal text-muted font-14">Total Simpanan</h6>
                        <h6 class="font-18"><i class="mdi mdi-arrow-up-bold-hexagon-outline text-primary"></i> <span class="text-dark">{{ number_format($saldo_debit) }}</span> </h6>
                    </div>
                </div>
            </div>
            <canvas id="chartPeriode" height="350" class="mt-4"></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="header-title m-t-0">CHART ANGGOTA KOPERASI</h4>
            <canvas id="chartAnggota" height="350" class="mt-4"></canvas>
        </div>
    </div>
</div> --}}
<!-- end row -->
<!-- end row -->
{{-- <div class="row">
    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box tilebox-one">
            <i class="fi-layers float-right"></i>
            <h6 class="text-muted text-uppercase mb-3">Kredit Pinjaman</h6>
            <h6 class="mb-3">{{ number_format($kredit_pinjaman) }}</h6>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box tilebox-one">
            <i class="fi-layers float-right"></i>
            <h6 class="text-muted text-uppercase mb-3">Debet Bunga</h6>
            <h6 class="mb-3"><span>{{ number_format($bunga_pinjaman) }}</span></h6>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
        <div class="card-box tilebox-one">
            <i class="fi-layers float-right"></i>
            <h6 class="text-muted text-uppercase mb-3">Debet Pinjaman</h6>
            <h6 class="mb-3"><span>{{ number_format($debet_pinjaman) }}</span></h6>
        </div>
    </div>
</div> --}}
<div class="row mt-3">
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="header-title mb-4">LOG PENGGUNA</h4>

            <div class="inbox-widget slimscroll" style="max-height: 370px;">
                @foreach ($activity_log as $item)
                    <a href="#">
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{ asset('images/users/avatar-1.jpg') }}" class="rounded-circle bx-shadow-lg" alt=""></div>
                            <p class="inbox-item-author">{{ $item->user->name }}</p>
                            <p class="inbox-item-text">{{ $item->description}}</p>
                            <p class="inbox-item-date">{{ Carbon\Carbon::parse($item->created_at)->format('g:i A') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="header-title mb-4">TRANSAKSI TERAKHIR</h4>
            <ul class="list-unstyled transaction-list slimscroll mb-0" style="max-height: 370px;">
                @foreach ($transaksi_harian as $item)
                    @if ($item->jenis_transaksi == '1')
                        <li>
                            <i class="dripicons-arrow-up text-success"></i>
                            <span class="tran-text">{{ $item->keterangan }}</span>
                            <span class="pull-right text-success tran-price">+{{ number_format($item->sumDebitAll->sum('nominal')) }}</span>
                            <span class="pull-right text-muted">{{ App\Helpers\Tanggal::tanggal_id($item->tgl) }}</span>
                            <span class="clearfix"></span>
                        </li>
                    @else
                        <li>
                            <i class="dripicons-arrow-down text-danger"></i>
                            <span class="tran-text">{{ $item->keterangan }}</span>
                            <span class="pull-right text-danger tran-price">-{{ number_format($item->sumKreditAll->sum('nominal'))}}</span>
                            <span class="pull-right text-muted">{{ App\Helpers\Tanggal::tanggal_id($item->tgl) }}</span>
                            <span class="clearfix"></span>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>

</div>
@endsection
@section('script')
<!-- Chart JS -->
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script>
    var ctx = document.getElementById('chartAnggota').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Non Aktif'],
            datasets: [{
                label: '# of Votes',
                data: {{ json_encode($status_anggota) }},
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        }
    });

    var data = {
            labels: {!! json_encode($months) !!},
            datasets: [
                {
                    label: "Pinjaman",
                    backgroundColor: "green",
                    data: {!! json_encode($kreditAll) !!}
                },
                {
                    label: "Simpanan",
                    backgroundColor: "blue",
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    data: {!! json_encode($debitAll) !!}
                },
            ]
    };

    var options = {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                        // Convert the number to a string and splite the string every 3 charaters from the end
                        value = value.toString();
                        value = value.split(/(?=(?:...)*$)/);

                        // Convert the array to a string and format the output
                        value = value.join('.');
                        return 'Rp ' + value;
                    }
                }
            }]
        }
    };

    var ctz = document.getElementById("chartPeriode").getContext("2d");
    var chartAnggota = new Chart(ctz, {
        type: 'bar',
        data: data,
        options: options
    });
</script>
@endsection
