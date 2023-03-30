<div>
    <!-- Section Page 1 -->
    <h3>Informasi</h3>
    <section>
        <div class="form-group clearfix">
            <label class="control-label " for="userName">Tanggal Transaksi</label>
            <div class="">
                {!! Form::text('tgl', null, ['class' => 'form-control required datepicker', 'autocomplete' => 'off', 'id'=> 'tgl'])!!}
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="control-label " for="password"> Jenis Pembayaran *</label>
            <div class="">
                {!! Form::select('jenis_pembayaran', ['1' => 'Cash', '2' => 'Bank'], null, ['placeholder' => '<---Jenis Transaksi -->', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="control-label " for="confirm">Kategori *</label>
            <input type="hidden" name="divisi_id" value="2">
            <input type="text" class="form-control" value="PINJAMAN" disabled>
            {{-- <div class="">
                {!! Form::select('divisi_id', [''=>'']+App\Models\Divisi::where('id', '2')->pluck('name','id')->all(), null, ['class' => 'form-control']) !!}
            </div> --}}
        </div>
        <div class="form-group clearfix anggota">
            <label class="control-label " for="confirm">No Anggota *</label>
            {!! Form::select('anggota_id', [''=>'Pilih Anggota']+App\Models\Anggota::pluck('nama','id')->all(), null, ['class' => 'form-control select2']) !!}
        </div>
        {{-- <div class="form-group clearfix anggota col-8">
            <table class="table">
                <tr>
                    <td><strong>Nama Anggota</strong></td>
                    <td>:</td>
                    <td id="nama-anggota"></td>
                </tr>
                <tr>
                    <td><strong>Nama Inisial</strong></td>
                    <td>:</td>
                    <td id="nama-inisial"></td>
                </tr>
                <tr>
                    <td><strong>Status Anggota</strong></td>
                    <td>:</td>
                    <td id="status-anggota"></td>
                </tr>
                <tr>
                    <td><strong>Tanggal Daftar</strong></td>
                    <td>:</td>
                    <td id="tanggal-daftar"></td>
                </tr>
            </table>
        </div> --}}
    </section>
    <!-- End Section Page 1 -->
    <!-- Section Page 2 -->
    <h3>Transaksi</h3>
    <section>
        <div class="form-group clearfix">
            <input type="hidden" name="jenis_transaksi" value="2">
            {{-- <label class="control-label" for="name"> Transaksi</label>
            <div class="">
                {!! Form::select('jenis_transaksi', ['2' => 'Kredit'], null, ['placeholder' => '<---Jenis Transaksi -->', 'class' => 'form-control']) !!}
            </div> --}}
        </div>
        <div id="pinjam-kredit">
            <div class="form-group clearfix">
                <label for="Pokok" class="control-label">Nominal</label>
                <div class="">
                    <input type="hidden" class="form-control" name="biaya_id" value="8">
                    {!! Form::text('nominal', null, ['class' => 'form-control biaya', 'autocomplete' => 'off','id' => 'nominal'])!!}
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="Pokok" class="control-label">Lama Kredit</label>
                <div class="">
                    {!! Form::select('lama_cicilan', ['3' => '3 Bulan Bunga(2%)', '6' => '6 Bulan Bunga(2%)', '9' => '9 Bulan Bunga(3%)', '12' => '12 Bulan Bunga(3%)'], null, ['placeholder' => '<---Lama Cicilan -->', 'class' => 'form-control', 'id'=>'lama_cicilan']) !!}
                </div>
            </div>
            <div class="form-group">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Jatuh Tempo Bayar Angsuran</th>
                            <th>Jumlah Nominal</th>
                        </tr>
                    </thead>
                    <tbody id="cicilan-skema">
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- end Section Page 2 -->
    <!-- Section Page 3 -->
    <h3>Keterangan</h3>
    <section>
        <div class="form-group clearfix">
            <label class="control-label " for="surname"> Keterangan </label>
            <div class="">
                {!! Form::textarea('keterangan', null, ['class' => 'form-control required', 'autocomplete' => 'off'])!!}
            </div>
        </div>
    </section>
    <!-- End Section Page 3 -->
</div>
