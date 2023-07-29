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
                <h4 class="page-title">Pinjaman Debet </h4>
                <small class="text-danger">Periode : {{ periode()->name }}</small>
            </div>
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                <li class="breadcrumb-item active"><a href="{{route('pinjaman-debet.index')}}">Pinjaman Debet</a></li>
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
            <form id="basic-form" action="{{ route('pinjaman-debet.store') }}" method="POST">
                @csrf
                @include('pinjaman-debet._form',['anggota'=>$anggota])
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
    let data = [];

        const check = (count,idx) => {
            var animals = $('[name="pinjaman_count"]');
            var total_bayar = 0;
            var bunga = $('#nominal_bunga_total').val();
            var fee = Math.ceil(parseInt(bunga) / count);
            var bunga_total = 0;
            var denda = 0;
            var price = 0;
            $.each(animals, function() {
                var $this = $(this);
                if($this.is(":checked")) {
                    total_bayar += parseInt($this.val()) + parseInt($($this).data("denda"));
                    bunga_total += fee;
                    denda += parseInt($($this).data("denda"));
                    price += parseInt($this.val());
                }
            });
            $('#nominal_pinjaman').val('Rp. '+total_bayar.toLocaleString('en-US'));
            $('#nominal_bunga').val(bunga_total);
            $('#denda').val(denda);
            $('[name="pinjaman_count"]').val(price);
        }

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
                    $('#nama-anggota').text(data.nama)
                    $('#nama-inisial').text(data.inisial)
                    $('#status-anggota').text(data.status)
                    $('#tanggal-daftar').text(data.tgl_daftar)
                    $.each(data.pinjaman, function (indexInArray, item) {
                        hitungCicilan(item.lama_cicilan,item.jumlah_pinjaman,item.angsuran_pinjaman,item.periode);
                    });
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

        function hitungCicilan(count,nominal,angsuran,tgl) {
            var fee = 0.02;
            var html = '';
            if(count>=9){
                fee = 0.03;
            }
            const rupiah = parseInt(nominal); // parse the numeric string to an integer value
            var nominal_bunga = rupiah * fee;
            var total = nominal_bunga + rupiah;
            var price = Math.ceil(total / count);
            var lunas = parseInt(angsuran) / price;
            var nominal_angsuran = 0;
            for (let i = 1; i <= count; i++) {
                var inp = '';
                var status = 'Lunas';
                let tgl_i = 20 + i;
                const start = moment(tgl).add(i, 'months');
                const end = moment();
                let telat = end.diff(start, "days");
                if(telat<=0){
                    telat = 0;
                }
                const denda = Math.ceil((0.1 * price) * telat);
                const total_ = price + denda;
                nominal_angsuran += total_;
                data.push({price:price,telat:telat,denda:denda,total:total_});
                if (lunas<=0) {
                    inp = `<input type="checkbox" class="pinjaman_id" onchange="check(${count},${i - 1})" name="pinjaman_count" value="${price}" data-denda="${denda}"/>`;
                    status = 'Belum Bayar';
                }
                html += '<tr><td class="text-center">'+inp+'</td><td>'+moment(tgl).add(i, 'months').format('DD/MM/YYYY')+'</td><td>'+telat+' Hari</td><td>'+denda.toLocaleString('en-US')+'</td><td>Rp. '+price.toLocaleString('en-US')+'</td><td>Rp. '+total_.toLocaleString('en-US')+'</td><td>'+status+'</td></tr>';
                lunas--;
            }
            html += `<tr>
                        <td style="font-weight:bold" class="text-center" colspan="5">Total Nominal Angsuran</td>
                        <td style="font-weight:bold" colspan="2">Rp. ${nominal_angsuran.toLocaleString('en-US')}</td>
                    </tr>`

            $('#cicilan-skema').append(html);
            $('#nominal_bunga_total').val(nominal_bunga);
        }
</script>
@endsection
