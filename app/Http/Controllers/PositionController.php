<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Position;
use App\Models\User;

use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    public function index()
    {        
        return view("position.index", ["positions" => DB::table("positions")->paginate(10)]);
    }

    public function create()
    {
        if(!$this->manages()) return redirect("/");
        return view("position.create");
    }

    public function store(Request $request)
    {
        if(!$this->manages()) return redirect("/");

        $validated = $request->validate(
            [
                "position"=> "required"
            ]
        );

        $pos = new Position;
        $pos->position = $request->input("position");
        $pos->save();

        return redirect("/position")->with("success", "Position Created");
    }

    public function edit($id)
    {
        if(!$this->manages()) return redirect("/");

        //send a 404 if id invalid
        Position::findOrFail($id); //exception not caught, send a 404 HTTP response to the client. 

        return view("position.edit")->with("id",$id);
    }

    public function update(Request $request, $id)
    {
        if(!$this->manages()) return redirect("/");

        $validated = $request->validate(
            [
                "position"=> "required"
            ]
        );

        $pos = Position::findOrFail($id); //exception not caught, send a 404 HTTP response to the client. 

        $pos->position = $request->input("position");
        $pos->save();

        return redirect("/position")->with("success", "Position Updated");
    }

    public function delete($id)
    {
        if(!$this->manages()) return redirect("/");

        $pos = Position::findOrFail($id);
        if($pos->delete())
        {
            return redirect("/position")->with("success", "Position Deleted");
        }
        else
        {
            return redirect("/position")->with("error", "An error has occurred");
        }
    }

    private function manages()
    {
        $user = User::find(auth()->user()->id);

        return $user->manager===1 || $user->admin===1;
    }
}
