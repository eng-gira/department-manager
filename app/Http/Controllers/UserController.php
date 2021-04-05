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
        return view("manager.index", 
            [
                "user_dept_pos" => 
                    DB::table("user_dept_pos")->paginate(10)
            ]
        );
    }

    public function setManager($id)
    {
        if(auth()->user()->admin === 1)
        {

        }
    }

    public function edit($id)
    {
        return auth()->user()->id === $id || $this->manages ?
            //view user.edit with user's department(s) and position(s)
            view("user.edit")->with( "user", DB::table("user_dept_pos")->where("user", $id) ) 
            :
            redirect("/dashboard")->with("error", "Unauthorized action")
        ;
    }

    public function update(Request $request, $id, $row_id)
    {        
        if($this->manages)
        {
            $validated = $request->validate(
                    [
                        "dept" => "required",
                        "pos"=> "required"
                    ]
            );

            $user = User::find(auth()->user()->id);
            
            if(DB::table("user_dept_pos")->where("id", $row_id)->exists())
            {
                $row = DB::table("user_dept_pos")->where("id", $row_id);

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
        else
        {
            return redirect("/dashboard")->with("error", "Unauthorized action");
        }
    }

    public function settings($id)
    {
        if(auth()->user()->id === $id) 
        {
            $user = User::find(auth()->user()->id);
         
            return view("user.settings")->with("user", $user);
        }
        else return redirect("/dashboard")->with("error", "Unauthorized Action");
    }
    public function updatePersonalInformation(Request $request, $id)
    {
        if(auth()->user()->id === $id)
        {
            $user = User::find(auth()->user()->id);

            $validated = $request->validate(
                [
                    "name" => "required|unique:users|min:3|max:50",
                    "email"=> "required|unique:users|min:11|max:50"
                ]
            );

            $user->name = $request->input("name");
            $user->email = $request->input("email");
            $user->password = Hash::make($request->input("password"));

            $user->save();

            return redirect("/dashboard")->with("success", "Records Updated");
        }
        
        return redirect("/dashboard")->with("error", "Unauthorized Action");
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
