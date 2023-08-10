<div>
    <!-- Section Page 1 -->
    <h3>Informasi</h3>
    <section>
        <div class="form-group clearfix">
            <label class="control-label" for="userName" id="datepicker">Tanggal Transaksi</label>
            <div class="">
                {!! Form::text('tgl', null, ['class' => 'form-control required datepicker', 'autocomplete' => 'off','id'=>'tgl'])!!}
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
            <input type="hidden" name="divisi_id" value="1">
            <input type="text" class="form-control" value="SIMPANAN" disabled>
            {{-- <div class="">
                {!! Form::select('divisi_id', [''=>'']+App\Models\Divisi::where('id', '1')->pluck('name','id')->all(), 1, ['class' => 'form-control']) !!}
            </div> --}}
        </div>
        <div class="form-group clearfix anggota">
            <label class="control-label " for="confirm">Nama Anggota *</label>
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
            {{-- <label class="control-label" for="name"> Transaksi</label> --}}
            <input type="hidden" name="jenis_transaksi" value="1">
            {{-- <input type="text" class="form-control" value="DEBET" disabled> --}}
            {{-- <div class="">
                {!! Form::select('jenis_transaksi', ['1' => 'Debet'], null, ['placeholder' => '<---Jenis Transaksi -->', 'class' => 'form-control']) !!}
            </div> --}}
        </div>
        <!-- Show DIvisi ID == 2 AND If Jenis Transaksi == 1 -->
        <div id="transaksi-debet">
            <div class="form-group clearfix">
                <label for="Pokok" class="control-label">Pokok</label>
                <div class="">
                    <input type="hidden" class="form-control" name="id_biaya_pokok" value="1">
                    <input type="text" class="form-control biaya" name="nominal_biaya_pokok" value="{{ old('nominal_biaya_pokok', $nominal_biaya_pokok ?? null) }}">
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="Pokok" class="control-label">Wajib</label>
                <div class="">
                    <input type="hidden" class="form-control" name="id_biaya_wajib" value="2">
                    <input type="text" class="form-control biaya" name="nominal_biaya_wajib" value="{{ old('nominal_biaya_wajib', $nominal_biaya_wajib ?? null) }}">
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="Pokok" class="control-label">Sukarela</label>
                <div class="">
                    <input type="hidden" class="form-control" name="id_biaya_sukarela" value="3">
                    <input type="text" class="form-control biaya" id="nominal_biaya_sukarela" name="nominal_biaya_sukarela" value="{{ old('nominal_biaya_sukarela', $nominal_biaya_sukarela ?? null) }}">
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="Pokok" class="control-label">Lama Simpanan</label>
                <div class="">
                    {!! Form::select('lama_simpanan', ['0'=>'Tidak Ada','3' => '3 Bulan Bunga(1%)', '6' => '6 Bulan Bunga(2%)', '9' => '9 Bulan Bunga(3%)', '12' => '12 Bulan Bunga(4%)'], 0, ['placeholder' => '<---Lama Simpanan -->', 'class' => 'form-control', 'id'=>'lama_simpanan']) !!}
                </div>
            </div>
            <div class="form-group">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Periode</th>
                            <th>Jumlah Nominal</th>
                        </tr>
                    </thead>
                    <tbody id="simpanan-skema">
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group clearfix">
                <label for="file" class="control-label">Bukti Transaksi</label>
                <div class="">
                    <input type="file" class="form-control" name="file">
                </div>
            </div>
        </div>
        <!-- End Show Jenis Transaksi == 1 -->
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
