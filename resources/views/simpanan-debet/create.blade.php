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
                <h4 class="page-title">Simpanan</h4>
                <small class="text-danger">Periode : {{ periode()->name }}</small>
            </div>
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                <li class="breadcrumb-item active"><a href="{{route('simpanan-debet.index')}}">Simpanan</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
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
            <form id="basic-form" action="{{ route('simpanan-debet.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('simpanan-debet._form')
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

        $('select[name=divisi_id]').on('change', function(){
            var divisiId = $(this). children("option:selected"). val();
            if(divisiId === '1' || divisiId === '2')
            {
                $('.anggota').css('display', '');
            }else {
                $('.anggota').css('display', 'none');
                $(".anggota option:selected").prop("selected", false);
            }
        });

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
                    let wajib = @json(number_format($wajib));
                    let pokok = @json(number_format($pokok));
                    $('#nama-anggota').text(data.nama)
                    $('#nama-inisial').text(data.inisial)
                    $('#status-anggota').text(data.status)
                    $('#tanggal-daftar').text(data.tgl_daftar)
                    $('#nominal_biaya_wajib').val('Rp. '+wajib);
                    $('#nominal_biaya_pokok').val('Rp. '+pokok);
                    if(data.is_wajib){
                        $('#nominal_biaya_wajib').val('Rp. 0');
                    }
                    if(data.is_pokok){
                        $('#nominal_biaya_pokok').val('Rp. 0');
                    }
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

        $('select[name=jenis_transaksi]').on('change', function(){

            var divisiId = $("select[name=divisi_id] option:selected").val();
            var jenisTransaksiId = $(this). children("option:selected"). val();
            if(divisiId === '1')
            {

                if(jenisTransaksiId === '1')
                {
                    $('#transaksi-debet').css('display', '');
                    $('#transaksi-kredit').css('display', 'none');
                }else {
                    $('#transaksi-debet').css('display', 'none');
                    $('#transaksi-kredit').css('display', '');
                }
            }

            if(divisiId === '2')
            {
                if(jenisTransaksiId === '1')
                {
                    $('#pinjam-debet').css('display', '');
                    $('#pinjam-kredit').css('display', 'none');
                }else {
                    $('#pinjam-debet').css('display', 'none');
                    $('#pinjam-kredit').css('display', '');
                }
            }
        });
    })

    function hitungSimpanan() {
        var count = $('#lama_simpanan').val();
        var nominal = $('#nominal_biaya_sukarela').val();
        var tgl = $('#tgl').val();
        var fee = 0;
        if(count==3){
            fee = 0.01;
        }else if(count==6){
            fee = 0.02;
        }else if(count==9){
            fee = 0.03;
        }else if(count==12){
            fee = 0.04;
        }
        var html = '';
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
                    <td style="font-weight:bold" class="text-center" colspan="2">Total Nominal Simpanan</td>
                    <td style="font-weight:bold">Rp. ${total.toLocaleString('en-US')}</td>
                </tr>`

        $('#simpanan-skema').html(html);
        $('#bunga').val(fee);
    }
    $('#lama_simpanan').change(function (e) {
        e.preventDefault();
        hitungSimpanan();
    });

    $('#nominal_biaya_sukarela').keyup(function (e) {
        hitungSimpanan();
    });
</script>
@endsection
