@extends('layouts.master')
@section('style')
<!-- DataTables -->
<link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="float-left">
                <h4 class="page-title">Approval Request Pinjaman </h4>
                <small class="text-danger">Periode : {{ periode()->name }}</small>
            </div>
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                <li class="breadcrumb-item active"><a href="{{route('laporan.cash-bank')}}">Approval Request Pinjaman</a></li>
                </ol>
                <small class="text-danger">Tahun Buku : {{ periode()->open_date }} - {{ periode()->close_date }}</small>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->
@if (session()->has('flash_notification.message'))
<div class="row">
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            {!! session()->get('flash_notification.message') !!}
        </div>
    </div>
</div>
@endif
<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <b>BAYAR PINJAMAN</b>
            <hr>
            <table id="datatable-buttons" class="table table-striped table-bordered display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Nama Anggota</th>
                        <th>Transaksi</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>File</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data2 as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->tgl)) }}</td>
                        <td>{{ $item->transaksi_harian_anggota->anggota->nama }}</td>
                        <td>{{ $item->jenis_pembayaran==1?'Kas':'Bank' }}</td>
                        <td>{{ number_format($item->transaksi_harian_biaya()->sum('nominal')) }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>
                            <a href="{{ asset($item->file) }}" target="d_blank">
                                <img src="{{ asset($item->file) }}" style="height:50px; width:50px;"/>
                            </a>
                        </td>
                        <td>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detail{{ $item->id }}">Detail</button>

                            <!-- Modal -->
                            <div class="modal fade" id="detail{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="detail{{ $item->id }}Label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('approval.update',$item) }}" method="POST" class="modal-content">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detail{{ $item->id }}Label">Detail</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Keterangan</th>
                                                        <th>Nominal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item->transaksi_harian_biaya as $idx => $detail)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $detail->biaya->name }}</td>
                                                        <td>{{ number_format($detail->nominal) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="status" value="1" onclick="return confirm('are you sure?')" class="btn btn-success">Approve</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-12 mt-3">
        <div class="card-box table-responsive">
            <b>AMBIL PINJAMAN</b>
            <hr>
            <table id="datatable-buttons1" class="table table-striped table-bordered display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Nama Anggota</th>
                        <th>Transaksi</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data1 as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->tgl)) }}</td>
                        <td>{{ $item->transaksi_harian_anggota->anggota->nama }}</td>
                        <td>{{ $item->jenis_pembayaran==1?'Kas':'Bank' }}</td>
                        <td>{{ number_format($item->transaksi_harian_biaya()->sum('nominal')) }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>
                            <form action="{{ route('approval.update',$item) }}" method="POST" class="modal-content">
                                @csrf
                                @method('PUT')
                                <button type="submit" name="status" value="1" onclick="return confirm('are you sure?')" class="btn btn-success">Approve</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@section('script')
<!-- Required datatable js -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
<!-- Buttons examples -->
<script src="{{ asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/buttons.print.min.js') }}"></script>
<script>
    var oTable = $('#datatable-buttons').DataTable()
    var oTable1 = $('#datatable-buttons1').DataTable()
</script>
@endsection
