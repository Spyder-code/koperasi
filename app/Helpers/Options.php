<?php

namespace App\Helpers;

use App\Models\Option;

class Options {

    public static function show($param)
    {
        $option = Option::select()->where('option_name', $param)->first();
        return $option->option_value;
    }
}
