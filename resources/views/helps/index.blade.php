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
                <h4 class="page-title">Help </h4>
                {{-- <small class="text-danger">Periode : {{ periode()->name }}</small> --}}
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
    <div class="col-8">
        <div class="card-box table-responsive">
            <table id="datatable-buttons" class="table table-striped table-bordered display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal Input</th>
                        <th>Keterangan</th>
                        <th>Tipe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ $item->tipe }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('help.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                    <div class="form-group">
                        <label for="tipe">Tipe</label>
                        <select name="tipe" id="tipe" class="form-control">
                            <option value="pertanyaan">Pertanyaan</option>
                            <option value="keluhan">Keluhan</option>
                            <option value="saran">Saran</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" cols="30" rows="4" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('are you sure?')">Kirim</button>
                </form>
            </div>
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
</script>
@endsection
