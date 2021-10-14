<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IoTController extends Controller
{
    //
    public function index()
    {
        $data = DB::table("iot")->get();

        return view("iot.index");
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'));

        foreach($data as $k=>$v)
            DB::insert('insert into iot (dataKey, dataValue) values (?, ?)', [$k, $v]);
    }
}
