<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Department;
use App\Models\User;

use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    public function index()
    {        
        return view("department.index", ["depts" => DB::table("departments")->paginate(10)]);
    }

    public function create()
    {
        if(!$this->manages()) return redirect("/dashboard")->with("error", "Unauthorized access");
        return view("department.create");
    }

    public function store(Request $request)
    {
        if(!$this->manages()) return redirect("/dashboard")->with("error", "Unauthorized access");

        $validated = $request->validate(
            [
                "department"=> "required"
            ]
        );

        $dept = new Department;
        $dept->department = $request->input("department");
        $dept->save();

        return redirect("/department")->with("success", "Department Created");
    }

    public function edit($id)
    {
        if(!$this->manages()) return redirect("/dashboard")->with("error", "Unauthorized access");

        //send a 404 if id invalid
        Department::findOrFail($id); //exception not caught, send a 404 HTTP response to the client. 

        return view("department.edit")->with("id",$id);
    }

    public function update(Request $request, $id)
    {
        if(!$this->manages()) return redirect("/dashboard")->with("error", "Unauthorized access");

        $validated = $request->validate(
            [
                "department"=> "required"
            ]
        );

        $dept = Department::findOrFail($id); //exception not caught, send a 404 HTTP response to the client. 

        $dept->department = $request->input("department");
        $dept->save();

        return redirect("/department")->with("success", "Department Updated");
    }

    public function delete($id)
    {
        if(auth()->user()->admin !== 1) return redirect("/dashboard")->with("error", "Unauthorized access");

        $dept = Department::findOrFail($id);
        $dept->delete();

        return redirect("/department")->with("success", "Department Deleted");
    }

    private function manages()
    {
        $user = User::find(auth()->user()->id);

        return $user->manager===1 || $user->admin===1;
    }
}
