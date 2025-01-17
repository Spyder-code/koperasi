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
                <h4 class="page-title">Pinjaman </h4>
                <small class="text-danger">Periode : {{ periode()->name }}</small>
            </div>
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                <li class="breadcrumb-item active"><a href="{{route('pinjaman-debet.index')}}">Pinjaman</a></li>
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
            <table id="datatable-buttons" class="table table-striped table-bordered display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Nama Anggota</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Divisi</th>
                        <th>Transaksi</th>
                        <th>Cicilan</th>
                        <th>Bunga</th>
                        <th>Denda</th>
                        <th>Keterangan</th>
                        <th>Status Buku</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
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
    $(document).ready(function(){
        var oTable = $('#datatable-buttons').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('pinjaman-debet.index') }}",
                        columns: [
                            { data: 'id', name: 'id' },
                            { data: 'nama_anggota.anggota.nama', name: 'nama_anggota.anggota.nama', orderable: false, searchable: false },
                            { data: 'tgl', name: 'tgl' },
                            { data: 'jenis_pembayaran', name: 'jenis_pembayaran' },
                            { data: 'divisi.name', name: 'divisi.name' },
                            { data: 'jenis_transaksi', name: 'jenis_transaksi' },
                            { data: 'sumCicilan', name: 'sumCicilan', orderable: false, searchable: false},
                            { data: 'sumBunga', name: 'sumBunga', orderable: false, searchable: false },
                            { data: 'sumDenda', name: 'sumDenda', orderable: false, searchable: false },
                            { data: 'keterangan', name: 'keterangan' },
                            { data: 'is_close', name: 'is_close' },
                            { data: 'file', name: 'file' },
                            { data: 'action', name: 'action', orderable: false, searchable: false}
                        ],
                        dom: '<"toolbar">frtip',
                        order: [[ 0, "desc" ]],
                        scrollX: true
                    });
                    $("div.toolbar").html(`@permission('create-debet-pinjaman')<a href="{{ route('pinjaman-debet.create') }}" class="btn btn-gradient waves-light waves-effect w-md"><i class="fa fa-plus"></i> Tambah</a>@endpermission
                    @permission('download-debet-pinjaman')<a href="https://docs.google.com/spreadsheets/d/1-fMrfyFf7ThvUuNbljLfJPGL19WR3yOl/export?format=xlsx" class="btn btn-success waves-light waves-effect w-md"><i class="mdi mdi-file-excel"></i>Download Template</a>@endpermission
                    @permission('upload-debet-pinjaman')<a href="{{ route('pinjaman-debet.upload') }}" class="btn btn-warning waves-light waves-effect w-md"><i class="mdi mdi-cloud-upload"></i> Upload</a>@endpermission
                    @if(App\Helpers\Pembukuan::closeBook('2', '1', ['6', '7']) == '1')
                        <a href="{{ route('pinjaman-debet.close-book') }}" class="btn btn-danger waves-light waves-effect w-md"><i class="fa fa-book"></i> Tutup Buku</a>
                    @endif
                    `);

    });
</script>
@endsection
