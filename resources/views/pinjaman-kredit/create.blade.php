@extends('layouts.master')
@section('style')
<!--Form Wizard-->
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/jquery.steps/css/jquery.steps.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
<link href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="float-left">
                <h4 class="page-title">Pinjaman Kredit </h4>
                <small class="text-danger">Periode : {{ periode()->name }}</small>
            </div>
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                <li class="breadcrumb-item active"><a href="{{route('pinjaman-kredit.index')}}">Pinjaman Kredit</a></li>
                    <li class="breadcrumb-item active">Tambag</li>
                </ol>
                <small class="text-danger">Tahun Buku : {{ periode()->open_date }} - {{ periode()->close_date }}</small>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><b>Menambah Transaksi Baru</b></h4>
            <p class="text-muted m-b-30 font-13">
                Silahkan Lakukan Pengisian Transaksi Secara Lengkap
            </p>
            <form id="basic-form" action="{{ route('pinjaman-kredit.store') }}" method="POST">
                @csrf
                @include('pinjaman-kredit._form')
            </form>

        </div>
    </div>
</div>
@endsection
@section('script')
<!--Form Wizard-->
<script src="{{ asset('plugins/jquery.steps/js/jquery.steps.min.js') }}" type="text/javascript"></script>

<!--wizard initialization-->
<script src="{{ asset('pages/jquery.wizard-init.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.maskMoney.js')}}" type="text/javascript"></script>
<script src="{{ asset('plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>
<script>
    $(function(){
        $(".select2").select2();

        $('.biaya').maskMoney({prefix: 'Rp. ', thousands: '.', decimal: ',', precision: 0});

        $('select[name=anggota_id]').on('change', function(){
            var anggotaId = $(this). children("option:selected"). val();
            $.ajax({
                url: '{{ route('transaksi-harian.chek-anggota')}}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    anggota_id: anggotaId
                },
                success: function(data)
                {
                    console.log(data)
                    $('#nama-anggota').text(data.nama)
                    $('#nama-inisial').text(data.inisial)
                    $('#status-anggota').text(data.status)
                    $('#tanggal-daftar').text(data.tgl_daftar)
                }
            });
        });

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

        console.log(moment('2023-01-01').add(3, 'months').format('DD MM YYYY'));
        function hitungCicilan() {
            var count = $('#lama_cicilan').val();
            var nominal = $('#nominal').val();
            var tgl = $('#tgl').val();
            var fee = 0.02;
            var html = '';
            if(count>=9){
                fee = 0.03;
            }
            const parts = tgl.split('-'); // split the date string by '-' character
            tgl = `${parts[2]}-${parts[1]}-${parts[0]}`; // rearrange the parts to get yyyy-mm-dd format
            const numericString = nominal.replace(/[^\d]/g, ''); // remove all non-numeric characters
            const rupiah = parseInt(numericString); // parse the numeric string to an integer value
            var total = (rupiah * fee) + rupiah;
            var price = Math.ceil(total / count);
            for (let i = 1; i <= count; i++) {
                html += `<tr>
                            <td class="text-center">${i}</td>
                            <td>${moment(tgl).add(i, 'months').format('DD/MM/YYYY')}</td>
                            <td>Rp. ${price.toLocaleString('en-US')}</td>
                        </tr>`;
            }
            html += `<tr>
                        <td style="font-weight:bold" class="text-center" colspan="2">Total Nominal Angsuran</td>
                        <td style="font-weight:bold">Rp. ${total.toLocaleString('en-US')}</td>
                    </tr>`

            $('#cicilan-skema').html(html);
            $('#bunga').val(fee);
        }
        $('#lama_cicilan').change(function (e) {
            e.preventDefault();
            hitungCicilan();
        });

        $('#nominal').keyup(function (e) {
            hitungCicilan();
        });
    })
</script>
@endsection
