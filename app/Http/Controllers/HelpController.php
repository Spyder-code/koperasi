<?php

namespace App\Http\Controllers;

use App\Models\Help;
use Illuminate\Http\Request;
use Session;

class HelpController extends Controller
{
    public function index()
    {
        $data = Help::all();
    return view('helps.index',compact('data'));
    }

    public function store(Request $request)
    {
        Help::create($request->all());
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Informasi berhasil terkirim !!!"
        ]);
        return back();
    }
}
