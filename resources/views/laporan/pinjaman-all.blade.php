@extends('layouts.master')
@section('style')
<link href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="float-left">
                <h4 class="page-title">Laporan Pinjaman </h4>
                <small class="text-danger">Periode : {{ periode()->name }}</small>
            </div>
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Laporan</a></li>
                <li class="breadcrumb-item active"><a href="{{route('laporan.pinjaman-all')}}">Laporan Pinjaman</a></li>
                </ol>
                <small class="text-danger">Tahun Buku : {{ periode()->open_date }} - {{ periode()->close_date }}</small>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->
<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <form id="periode" action="{{ route('laporan.pinjaman-all') }}" method="GET">
                @csrf
                <div class="form-group">
                    <label for="">Tanggal Awal</label>
                    <input type="text" class="form-control datepicker" name="start_date" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="">Tanggal Akhir</label>
                    <input type="text" class="form-control datepicker" name="end_date" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="">Nama Anggota</label>
                    <select name="id_anggota" id="id_anggota" class="form-control">
                        <option value=""></option>
                        @foreach ($anggota as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="submit" value="Cari" class="btn btn-primary" name="search">
                {{-- @role('admin')
                <input type="submit" value="Excell" class="btn btn-danger" name="export_excell">
                @endrole --}}
            </form>
        </div>
    </div>
</div>
@if (request('search'))
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tgl</th>
                        <th>Keterangan</th>
                        <th>Pinjaman</th>
                        <th>Cicilan Pinjaman</th>
                        <th>Akumulasi Denda</th>
                        <th>Akumulasi Bunga</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                @if (!empty($transaksi_harian))
                    <tbody>
                        @php
                            $no = 1;
                            $saldo = 0;
                            $cicilan = 0;
                            $sumKredit = 0;
                            $sumCicilan = 0;
                            $i = 0;
                        @endphp
                        @php
                            $saldo =  $sum_kredit_pinjaman - $sum_cicilan;
                        @endphp
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td><strong>Saldo Mutasi</strong></td>
                            <td>{{ App\Helpers\Money::stringToRupiah($sum_kredit_pinjaman) }}</td>
                            <td>{{ App\Helpers\Money::stringToRupiah($sum_cicilan) }}</td>
                            <td>{{ App\Helpers\Money::stringToRupiah($sum_bunga) }}</td>
                            <td>{{ App\Helpers\Money::stringToRupiah($saldo) }}</td>
                        </tr>
                        @foreach ($transaksi_harian as $row)
                            @php
                                $saldo +=  $row->sumKreditPinjaman->sum('nominal') - $row->sumCicilan->sum('nominal');
                            @endphp
                            <tr>
                                <th scope="row">{{ $no }}</th>
                                <td>{{ App\Helpers\Tanggal::tanggal_id($row->tgl) }}</td>
                                <td>{{ $row->keterangan }}</td>
                                <td>{{ App\Helpers\Money::stringToRupiah($row->sumKreditPinjaman->sum('nominal')) }}</td>
                                <td>{{ App\Helpers\Money::stringToRupiah($row->sumCicilan->sum('nominal')) }}</td>
                                <td>{{ App\Helpers\Money::stringToRupiah($row->sumDenda->sum('nominal')) }}</td>
                                <td>{{ App\Helpers\Money::stringToRupiah($row->sumBunga->sum('nominal')) }}</td>
                                <td>{{ App\Helpers\Money::stringToRupiah($saldo) }}</td>
                            </tr>
                            @php
                                $no++;
                            @endphp
                        @endforeach
                    </tbody>
                @endif
            </table>            
        </div>
    </div>
</div>
@endif
{{-- <div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <form id="anggota-form">
                <table class="table-small-font">
                    <thead>
                        <tr>
                            <th>Nama Anggota :</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anggota as $item)
                            <td class="checkbox checkbox-success form-check-inline">
                                <input type="checkbox" id="inlineCheckbox{{$item->id}}" value="{{$item->id}}" name="anggota[]">
                                <label for="inlineCheckbox{{$item->id}}"> {{ $item->nama }} </label>
                            </td>
                        @endforeach
                    </tbody>
                </table>
                <button class="btn btn-success text-right" type="button" id="export">Lihat Laporan</button>
            </form>
        </div>
    </div>
</div> --}}
@endsection
@section('script')
<script src="{{ asset('plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script>
    $(function(){
        $(".select2").select2();

        var start = new Date('{{ periode()->open_date }}');
        var end = new Date('{{ periode()->close_date }}');

        $(".datepicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            startDate: start,
            endDate   : end,
            orientation: 'bottom'
        });

        $('#export').click(function(e){
            var data = $('#anggota-form, #periode').serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('laporan-pinjaman-all.validation')}}',
                data: data,
                success: function(result, status, xhr)
                {
                    if(result.error){

                        console.log("Jika Error Selain Dari Kosong Ke Error");
                        $.toast({
                            heading: 'Error !',
                            text: result.error,
                            position: 'top-right',
                            loaderBg: '#bf441d',
                            icon: 'error',
                            hideAfter: 3000,
                            stack: 1
                        });
                    }else {
                        $.ajax({
                            xhrFields: {
                                responseType: 'blob',
                            },
                            type: 'POST',
                            url: '{{ route('laporan-pinjaman-all.export')}}',
                            data: data,
                            success: function(result, status, xhr)
                            {
                                var disposition = xhr.getResponseHeader('content-disposition');
                                var matches = /"([^"]*)"/.exec(disposition);
                                var filename = (matches != null && matches[1] ? matches[1] : 'salary.xlsx');
                                // The actual download
                                var blob = new Blob([result], {
                                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                });
                                var link = document.createElement('a');
                                link.href = window.URL.createObjectURL(blob);
                                link.download = filename;
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                            }
                        });
                    }
                }
            })
        });
    })
</script>
@endsection
