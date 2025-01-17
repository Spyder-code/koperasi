<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Anggota;
use App\Models\User;
use App\Models\UserAnggota;
use Laratrust\Models\LaratrustRole as Role;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $anggota = new Anggota();
        $anggota->nik = '320209040401001';
        $anggota->nama = 'Agus Permana';
        $anggota->inisial = 'AP';
        $anggota->homebase = 'Surabaya';
        $anggota->tgl_daftar = date('Y-m-d');
        $anggota->status = '1';
        $anggota->save();

        $user = User::create([
            'name' => $anggota->nama,
            'email' => $anggota->nik,
            'username' => $anggota->nik,
            'password' => bcrypt('password')
        ]);

        $user_anggota = new UserAnggota();
        $user_anggota->anggota_id = $anggota->id;
        $user_anggota->user_id = $user->id;
        $user_anggota->save();
        $roleUser = Role::where('name', 'member')->first();
        $user->attachRole($roleUser);

        $anggota = new Anggota();
        $anggota->nik = '320209050501002';
        $anggota->nama = 'Ahmad Jaelani';
        $anggota->inisial = 'AJ';
        $anggota->homebase = 'Surabaya';
        $anggota->tgl_daftar = date('Y-m-d');
        $anggota->status = '1';
        $anggota->save();

        $user = User::create([
            'name' => $anggota->nama,
            'email' => $anggota->nik,
            'username' => $anggota->nik,
            'password' => bcrypt('password')
        ]);

        $user_anggota = new UserAnggota();
        $user_anggota->anggota_id = $anggota->id;
        $user_anggota->user_id = $user->id;
        $user_anggota->save();

        $user->attachRole($roleUser);

        $anggota = new Anggota();
        $anggota->nik = '320209060601003';
        $anggota->nama = 'Miranda';
        $anggota->inisial = 'M';
        $anggota->homebase = 'Surabaya';
        $anggota->tgl_daftar = date('Y-m-d');
        $anggota->status = '1';
        $anggota->save();

        $user = User::create([
            'name' => $anggota->nama,
            'email' => $anggota->nik,
            'username' => $anggota->nik,
            'password' => bcrypt('password')
        ]);

        $user_anggota = new UserAnggota();
        $user_anggota->anggota_id = $anggota->id;
        $user_anggota->user_id = $user->id;
        $user_anggota->save();

        $user->attachRole($roleUser);

        $anggota = new Anggota();
        $anggota->nik = '320209070701004';
        $anggota->nama = 'Raka Hadi';
        $anggota->inisial = 'RH';
        $anggota->homebase = 'Surabaya';
        $anggota->tgl_daftar = date('Y-m-d');
        $anggota->status = '1';
        $anggota->save();

        $user = User::create([
            'name' => $anggota->nama,
            'email' => $anggota->nik,
            'username' => $anggota->nik,
            'password' => bcrypt('password')
        ]);

        $user_anggota = new UserAnggota();
        $user_anggota->anggota_id = $anggota->id;
        $user_anggota->user_id = $user->id;
        $user_anggota->save();

        $user->attachRole($roleUser);

        $anggota = new Anggota();
        $anggota->nik = '320209080801005';
        $anggota->nama = 'Hermansyah';
        $anggota->inisial = 'H';
        $anggota->homebase = 'Surabaya';
        $anggota->tgl_daftar = date('Y-m-d');
        $anggota->status = '1';
        $anggota->save();

        $user = User::create([
            'name' => $anggota->nama,
            'email' => $anggota->nik,
            'username' => $anggota->nik,
            'password' => bcrypt('password')
        ]);

        $user_anggota = new UserAnggota();
        $user_anggota->anggota_id = $anggota->id;
        $user_anggota->user_id = $user->id;
        $user_anggota->save();

        $user->attachRole($roleUser);

        $anggota = new Anggota();
        $anggota->nik = '320209080801006';
        $anggota->nama = 'Angga Rojak';
        $anggota->inisial = 'H';
        $anggota->homebase = 'Surabaya';
        $anggota->tgl_daftar = date('Y-m-d');
        $anggota->status = '1';
        $anggota->save();

        $user = User::create([
            'name' => $anggota->nama,
            'email' => $anggota->nik,
            'username' => $anggota->nik,
            'password' => bcrypt('password')
        ]);

        $user_anggota = new UserAnggota();
        $user_anggota->anggota_id = $anggota->id;
        $user_anggota->user_id = $user->id;
        $user_anggota->save();

        $user->attachRole($roleUser);

        $anggota = new Anggota();
        $anggota->nik = '320209080801007';
        $anggota->nama = 'Kardun';
        $anggota->inisial = 'H';
        $anggota->homebase = 'Surabaya';
        $anggota->tgl_daftar = date('Y-m-d');
        $anggota->status = '1';
        $anggota->save();

        $user = User::create([
            'name' => $anggota->nama,
            'email' => $anggota->nik,
            'username' => $anggota->nik,
            'password' => bcrypt('password')
        ]);

        $user_anggota = new UserAnggota();
        $user_anggota->anggota_id = $anggota->id;
        $user_anggota->user_id = $user->id;
        $user_anggota->save();

        $user->attachRole($roleUser);

        $anggota = new Anggota();
        $anggota->nik = '320209080801008';
        $anggota->nama = 'Jaja Miharja';
        $anggota->inisial = 'H';
        $anggota->homebase = 'Surabaya';
        $anggota->tgl_daftar = date('Y-m-d');
        $anggota->status = '1';
        $anggota->save();

        $user = User::create([
            'name' => $anggota->nama,
            'email' => $anggota->nik,
            'username' => $anggota->nik,
            'password' => bcrypt('password')
        ]);

        $user_anggota = new UserAnggota();
        $user_anggota->anggota_id = $anggota->id;
        $user_anggota->user_id = $user->id;
        $user_anggota->save();

        $user->attachRole($roleUser);

        $anggota = new Anggota();
        $anggota->nik = '320209080801009';
        $anggota->nama = 'Miharja';
        $anggota->inisial = 'H';
        $anggota->homebase = 'Surabaya';
        $anggota->tgl_daftar = date('Y-m-d');
        $anggota->status = '1';
        $anggota->save();

        $user = User::create([
            'name' => $anggota->nama,
            'email' => $anggota->nik,
            'username' => $anggota->nik,
            'password' => bcrypt('password')
        ]);

        $user->attachRole($roleUser);

        $user_anggota = new UserAnggota();
        $user_anggota->anggota_id = $anggota->id;
        $user_anggota->user_id = $user->id;
        $user_anggota->save();

        $anggota = new Anggota();
        $anggota->nik = '320209080801010';
        $anggota->nama = 'Santika';
        $anggota->inisial = 'H';
        $anggota->homebase = 'Surabaya';
        $anggota->tgl_daftar = date('Y-m-d');
        $anggota->status = '1';
        $anggota->save();

        $user = User::create([
            'name' => $anggota->nama,
            'email' => $anggota->nik,
            'username' => $anggota->nik,
            'password' => bcrypt('password')
        ]);

        $user->attachRole($roleUser);

        $user_anggota = new UserAnggota();
        $user_anggota->anggota_id = $anggota->id;
        $user_anggota->user_id = $user->id;
        $user_anggota->save();
    }
}
