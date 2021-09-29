<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Department;
use App\Models\User;
use App\Models\Position;

use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class DepartmentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth', ['except' => ["index", "single"]]);
    }

    public function index()
    {        
        $auth = User::find(auth()->user());
        $manages = $auth !== null ? $this->manages() : 0;

        return view("department.index")->with(
            [
                "depts" => DB::table("departments")->paginate(10),
                "manages" => intval($manages),
                "admin" => $auth!==null? auth()->user()->admin : 0,
                "auth" => $auth !== null
            ]
        );
    }

    //show a single department's positions and users
    public function single($id)
    {
        //check validity of id, and 404 if invalid.
        $dept = Department::findOrFail($id);

        $users = User::all(); 
        $userIdNameArr = []; //associative array pairing user's id and name
        foreach($users as $u) $userIdNameArr[$u->id] = $u->name;
        

        $positions = Position::all();
        $posIdNameArr = []; // associative array pairing position's id and position
        foreach($positions as $p) $posIdNameArr[$p->id] = $p->position;

        //formatting the date
        $createdOn = explode(" ", $dept->created_at)[0];
        $date = explode("-", $createdOn);
        $yyyy = $date[0];
        $mm = $date[1];
        $dd = $date[2];

        $countUsersInDept = count(DB::table("user_dept")->where("department", $id)->get());

        return view("department.single",
            [
                "dept" => $dept, 
                "userIdNameArr" => $userIdNameArr, 
                "posIdNameArr" => $posIdNameArr,
                "createdOn" => $dd . "." . $mm . "." . $yyyy,
                "countUsersInDept" => $countUsersInDept,
                "manages" => $this->manages()
            ]
        );
    }

    public function saveBackgroundImage(Request $request, $id)
    {
        $validated = $request->validate(
            [
                "bg_image" => "max:5000"
            ]
        );

        $dept = Department::findOrFail($id);

        //using Cloudinary to upload photos
        $uploadedFileUrl = Cloudinary::upload($request->file("bg_image")->getRealPath())->getSecurePath();
        $dept->background_image_url = $uploadedFileUrl;
        $dept->save();
        
        return redirect("/department/$id");
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
        $dept = Department::findOrFail($id); //exception not caught, send a 404 HTTP response to the client. 

        return view("department.edit")->with(
            [
                "id" => $id,
                "dept" => $dept
            ]
        );
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
        $auth = User::find(auth()->user());
        $user = $auth !== null ? User::find(auth()->user()->id) : null;

        return $user === null ? 0 : ($user->manager===1 || $user->admin===1);
    }
    
}
