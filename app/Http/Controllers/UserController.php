<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Department;
use App\Models\Position;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth', ["except" => ["dashboard"]]);
    }

    public function dashboard()
    {
        // //debug
        // echo "dashboard from user controller <br>";

        $users = User::all(); 
        $userIdDataArr = []; //associative array pairing user's id and name
        foreach($users as $u) $userIdDataArr[$u->id] = $u;

        $departments = Department::all();
        $deptIdNameArr = []; // associative array pairing dept's id and department
        foreach($departments as $d) $deptIdNameArr[$d->id] = $d->department;

        $positions = Position::all();
        $posIdNameArr = []; // associative array pairing position's id and position
        foreach($positions as $p) $posIdNameArr[$p->id] = $p->position;


        $auth = User::find(auth()->user());
        
        /**
         * @todo use pagination with user_dept
         */


        return view("dashboard")->with( 
                [
                    "manages" => intval($this->manages()),
                    "admin" => $auth !== null ? intval(auth()->user()->admin) : 0, 
                    "user_dept" => DB::table("user_dept")->paginate(5), 
                    "userIdDataArr" => $userIdDataArr,
                    "deptIdNameArr" => $deptIdNameArr, 
                    "posIdNameArr" => $posIdNameArr,
                    "auth" => $auth !== null ? auth()->user() : 0, 
                ]
            );
    }
    public function listManagers()
    {
        if(!$this->manages())
        {
            return redirect("/dashboard")->with("error", "Unauthorized action.");
        }
        else
        {
            $managers = User::where("manager", "=", 1)->where("admin", "<>", 1)->get();
            return view("managers")->with(
                [
                    "managers" => $managers, 
                    "admin" => auth()->user()->admin
                ]
            );
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
            $users = User::where("manager", "<>", 1)->where("admin", "<>", 1)->get();
            return view("users")->with(
                [
                    "users" => $users,
                    "admin" => auth()->user()->admin
                ]
            );
        }        
    }

    public function setManager($id)
    {
        // only the admin can set a user as a manager
        if(auth()->user()->admin === 1)
        {
            $user = User::findOrFail($id);
            $user->manager = 1;
            if($user->save())
            {
                return redirect("/listManagers")->with("success", "User set as manager");
            }
            else
            {
                return redirect("/listManagers")->with("error", "User was not set as manager");    
            }

            return redirect("/dashboard")->with("error", "Unauthorized Action");
        }
    }

    public function unsetManager($id)
    {
        if(auth()->user()->admin === 1)
        {
            $user = User::findOrFail($id);
            $user->manager = 0;
            $user->save();
            
            return redirect("/listManagers")->with("success", "Manager was uset");
        }   

        return redirect("/dashboard")->with("error", "Failed to unset manager");
    }

    public function deleteUserDeptConnection($row_id)
    {
        if(auth()->user()->admin) 
        {
            DB::table("user_dept")->delete($row_id);
            return redirect("/dashboard")->with("success", "User was removed from the department");
        }
        return redirect("/dashboard")->with("error", "Something went wrong.");
    }

    public function edit($id)
    {
        return auth()->user()->id === $id || $this->manages() ?
            //view user.edit with user's department(s) and position(s)
            view("user.edit")->with( 
                [
                    "id" => $id,  
                    "depts" => Department::all(), 
                    "positions" => Position::all(), 
                    "user" => User::findOrFail($id)
                ]
            ) 
            :
            redirect("/dashboard")->with("error", "Unauthorized action")
        ;
    }

    public function update(Request $request, $id)
    {     

        //
        if(auth()->user()->admin === 0 && User::find($id)->admin===1) 
        {         
            return redirect("/dashboard");
        }

        //debug
        echo "arrived this->manages(): " . $this->manages() ? "true" : "false";
                
        if($this->manages())
        {
            $validated = $request->validate(
                    [
                        "dept" => "required",
                        "pos"=> "required"
                    ]
            );

            //debug
            echo "arrived 4 <br>";

            $user = User::find($id);
            
            // if(DB::table("user_dept_pos")->where("id", $row_id)->exists())
            // {
            //     $row = DB::table("user_dept_pos")->where("id", $row_id)->get();

            //     DB::table("user_dept_pos")->where("id", $row_id)->update(
            //         [
            //             "department"=>$request->input("dept"), 
            //             "position"=>$request->input("pos")
            //         ]
            //     );
            // }
            // else
            // {
                $rows = DB::table("user_dept")->where([
                    ["user", "=", $id],
                ])->get();

                 echo "arrived 5 <br>";

                $new_dept = $request->input("dept");

                $found = 0;
                foreach($rows as $row)
                {
                    if($row->department == $new_dept)
                    {
                        $found = 1;
                    }
                }

                if($found === 0) 
                {
                    DB::table("user_dept")->insert(
                        [
                            "user"=>$id,
                            "department"=>$request->input("dept")
                        ]
                    );
                }

                echo "arrived 6 <br>";

                $user->position = $request->input("pos");
                $user->save();

                echo "arrived 7<br>";


            // }

            return $user->manager===1 ? 
                redirect("/dashboard")->with("success", "User status updated") : 
                redirect("/dashboard")->with("success", "User status updated");
        }
        else
        {
            return redirect("/dashboard")->with("error", "Unauthorized action");
        }
    }

    // public function settings($id)
    // {
    //     if(auth()->user()->id === $id) 
    //     {
    //         $user = User::find(auth()->user()->id);
         
    //         return view("user.settings")->with(
    //             [
    //                 "user" => $user,
    //                 "auth_id" => auth()->user()->id   
    //             ]
    //         );
    //     }
    //     else return redirect("/dashboard")->with("error", "Unauthorized Action");
    // }
    public function updatePersonalInformation(Request $request, $id)
    {
        if(auth()->user()->id === $id)
        {
            $user = User::find(auth()->user()->id);

            $validated = $request->validate(
                [
                    "name" => "required|min:3",
                    "email"=> "required|email"
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
        $auth = User::find(auth()->user());
        $user = $auth !== null ? User::find(auth()->user()->id) : null;

        return $user === null ? 0 : ($user->manager===1 || $user->admin===1);
    }
}
