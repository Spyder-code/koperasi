<?php

namespace Database\Seeders;
use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $option = new Option();
        $option->option_name = 'footer';
        $option->option_value = 'MEDI Software Development';
        $option->save();

        $option = new Option();
        $option->option_name = 'title_text';
        $option->option_value = 'MEDI Koperasi';
        $option->save();

        $option = new Option();
        $option->option_name = 'footer_text';
        $option->option_value = 'MEDI Koperasi';
        $option->save();

        $option = new Option();
        $option->option_name = 'site_currency_symbol';
        $option->option_value = 'Rp ';
        $option->save();

        $option = new Option();
        $option->option_name = 'company_option_name';
        $option->option_value = 'KOPERASI SIMPAN PINJAM';
        $option->save();

        $option = new Option();
        $option->option_name = 'company_address';
        $option->option_value = 'Surabaya';
        $option->save();

        $option = new Option();
        $option->option_name = 'company_phone';
        $option->option_value = '083857317946';
        $option->save();

        $option = new Option();
        $option->option_name = 'company_email';
        $option->option_value = 'luaysyauqillah@gmail.com';
        $option->save();

        $option = new Option();
        $option->option_name = 'postal_code';
        $option->option_value = '';
        $option->save();

        $option = new Option();
        $option->option_name = 'company_fb';
        $option->option_value = 'facebook.com';
        $option->save();

        $option = new Option();
        $option->option_name = 'company_ig';
        $option->option_value = 'instagram.com';
        $option->save();

        $option = new Option();
        $option->option_name = 'company_twitter';
        $option->option_value = 'twitter.com';
        $option->save();

        $option = new Option();
        $option->option_name = 'company_yt';
        $option->option_value = 'youtube.com';
        $option->save();

        $option = new Option();
        $option->option_name = 'phone_wa';
        $option->option_value = '+6283857317946';
        $option->save();

        $option = new Option();
        $option->option_name = 'text_wa';
        $option->option_value = 'Hallo, MEDI, Saya Dapat Info dari Website';
        $option->save();

        $option = new Option();
        $option->option_name = 'text_maintenance';
        $option->option_value = 'Hallo, MEDI, Saat Ini Sedang Maintenance';
        $option->save();
    }
}
