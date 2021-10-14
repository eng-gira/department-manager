<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IoTController extends Controller
{
    //
    public function index()
    {
        $dataFromDB = json_encode(DB::table("iot")->get());
        $keys = [];
        $vals = [];
        $data = [];
        var_dump(json_decode($dataFromDB));
        foreach(json_decode($dataFromDB) as $k=>$v)
        {
            if($k=="dataKey")
            {
                $keys[count($keys)] = $v;
            }
            elseif($k=="dataValue")
            {
                $vals[count($vals)] = $v;
            }
        }

        if(count($keys) != count($vals))
        {
            $data = "An Error occurred";
        }   
        else
        {
            for($i=0; $i < count($keys); $i++)
            {
                $data[$keys[$i]] = $vals[$i];
            }
        }
        return view("iot.index")->with(["data" => $data]);
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'));

        foreach($data as $k=>$v)
            DB::insert('insert into iot (dataKey, dataValue) values (?, ?)', [$k, $v]);
    }
}
