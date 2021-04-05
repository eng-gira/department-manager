<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        // //debug
        // echo "dashboard from user controller <br>";

        return view("dashboard");
    }

    public function admin()
    {
        if(!$this->manages()) return redirect("/");

        return view("admin.index", ["user_dept_pos" => DB::table("user_dept_pos")->paginate(10)]);
    }

    public function listManagers()
    {
        if(!$this->manages())
        {
            return redirect("/dashboard")->with("error", "Unauthorized action.");
        }
        else
        {
            $managers = User::where("manager", "=", 1);
            return view("managers")->with("managers", $managers);
        }
    }

    public function listUsers()
    {
        if(!$this->manages())
        {
            return redirect("/dashboard")->with("error", "Unauthorized action.");
        }
        else
        {
            $users = User::all();
            return view("users")->with("users", $users);
        }        
    }

    public function manager()
    {
        return view("manager.index", ["user_dept_pos" => DB::table("user_dept_pos")->paginate(10)]);
    }

    public function edit($id)
    {
        return view("user.edit");        
    }

    public function update(Request $request, $id, $row_id)
    {
        $user = User::find(auth()->user()->id);

        if($user->id === $id)
        {
            $validated = $request->validate(
                [
                    "name" => "required|unique:users|min:3|max:50",
                    "email"=> "required|unique:users|min:11|max:50"
                ]
            );

            $user->name = $request->input("name");
            $user->email = $request->input("email");
            $user->password = Hash::make($request->input("password"));

            return redirect("/dashboard")->with("success", "Records Updated");
        }
        else if($this->manages)
        {
            $validated = $request->validate(
                [
                    "dept" => "required",
                    "pos"=> "required"
                ]
            );

            if(DB::table("user_dept_pos")->where("id", $row_id)->exists())
            {
                $row = DB::table("user_dept_pos")->where("id", $row_id);

                $old_dept = $row->department;
                $old_pos = $row->position;

                $new_dept = $request->input("dept");
                $new_pos = $request->input("pos");

                //no need to update
                if($old_dept === $new_dept && $new_pos===$old_pos)
                {
                    return $user->manager===1 ? redirect("/manager") : redirect("/admin");
                }

                DB::table("user_dept_pos")->where("id", $row_id)->update(
                    [
                        "dept"=>$request->input("dept"), 
                        "pos"=>$request->input("pos")
                    ]
                );
            }
            else
            {
                $rows = DB::table("user_dept_pos")->where([
                    ["user", "=", $id],
                ])->get();

                $new_dept = $request->input("dept");
                $new_pos = $request->input("pos");

                ///user cannot have two positions in a single department
                foreach($rows as $row)
                {
                    if($row->value("department") == $new_dept)
                    {
                        $message = "User already has position in the department";

                        return $user->manager===1 ? 
                            redirect("/manager")->with("error", $message) : 
                            redirect("/admin")->with("error", $message);
                    }
                }

                DB::table("user_dept_pos")->insert(
                    [
                        "dept"=>$request->input("dept"), 
                        "pos"=>$request->input("pos")
                    ]
                );
            }

            return $user->manager===1 ? 
                redirect("/manager")->with("success", "User status updated") : 
                redirect("/admin")->with("success", "User status updated");
        }
    }

    public function delete($id)
    {
        //only admin and the owner of an account can delete that account
        if(auth()->user()->admin===1 || auth()->user()->id === $id)
        {
            $user = User::find(auth()->user()->id);

            //if not deleting own account
            if(auth()->user()->id !== $id)
            {
                if($user->delete())
                {
                    return redirect("/dashboard")->with("success", "User deleted");
                }
                return redirect("/dashboard")->with("error", "User not deleted");
            }
            else
            {
                //logout from own account before deleting
                Auth::logout();

                if($user->delete()) return redirect("/")->with("success", "Your account has been deleted");
                return redirect("/")->with("error", "Your account was not deleted");
            }
        }
        else
        {
            return redirect("/dashboard")->with("error", "Unauthorized action");
        }
    }

    private function manages()
    {
        $user = User::find(auth()->user()->id);

        return $user->manager===1 || $user->admin===1;
    }
}
