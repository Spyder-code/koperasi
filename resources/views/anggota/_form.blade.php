<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">NO Anggota</label>
    <div class="col-8">
        {{ Form::text('nik', null, ['class' => $errors->has('nik') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'NIK']) }}
        @error('nik')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">Nama</label>
    <div class="col-8">
        {{ Form::text('nama', null, ['class' => $errors->has('nama') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Nama Anggota']) }}
        @error('nama')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">Nama Inisial</label>
    <div class="col-8">
        {{ Form::text('inisial', null, ['class' => $errors->has('inisial') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Nama Inisial']) }}
        @error('inisial')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">Tanggal Lahir</label>
    <div class="col-8">
        {{ Form::text('tgl_lahir', null, ['class' => $errors->has('tgl_lahir') ? 'form-control datepicker is-invalid' : 'form-control datepicker', 'placeholder' => 'Tanggal Lahir']) }}
        @error('tgl_lahir')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">Tempat Lahir</label>
    <div class="col-8">
        {{ Form::text('tempat_lahir', null, ['class' => $errors->has('tempat_lahir') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Tempat Lahir']) }}
        @error('tempat_lahir')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">Tanggal Daftar</label>
    <div class="col-8">
        {{ Form::text('tgl_daftar', null, ['class' => $errors->has('tgl_daftar') ? 'form-control datepicker is-invalid' : 'form-control datepicker', 'placeholder' => 'Tanggal Daftar']) }}
        @error('tgl_daftar')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">Status</label>
    <div class="col-8">
        {!! Form::select('status', ['1' => 'Aktif', '0' => 'Non Aktif'], null, ['class'=>$errors->has('status') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Pilih Status']) !!}
        @error('status')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">Jabatan</label>
    <div class="col-8">
        {{ Form::text('jabatan', null, ['class' => $errors->has('jabatan') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Jabatan']) }}
        @error('jabatan')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">Alamat</label>
    <div class="col-8">
        {!! Form::text('homebase', null, ['class'=>$errors->has('homebase') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Alamat']) !!}
        @error('homebase')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">Bank</label>
    <div class="col-8">
        {{ Form::text('bank', null, ['class' => $errors->has('bank') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Bank']) }}
        @error('bank')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">No. Rekening</label>
    <div class="col-8">
        {{ Form::text('no_rek', null, ['class' => $errors->has('no_rek') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'No. Rekening']) }}
        @error('no_rek')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<hr>
<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">Username</label>
    <div class="col-8">
        {{ Form::text('username', null, ['class' => $errors->has('username') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'Username Anggota']) }}
        @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-4 col-form-label" for="example-input-normal">Password</label>
    <div class="col-8">
        {{ Form::password('password', null, ['class' => $errors->has('password') ? 'form-control is-invalid' : 'form-control']) }}
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<button type="submit" class="btn btn-primary">Simpan</button>
<a href="{{ route('anggota.index') }}" class="btn btn-help-block">Kembali</a>
