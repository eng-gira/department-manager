<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Position;
use App\Models\Department;
use App\Models\User;

class UserController extends Controller
{
    //
    public function admin()
    {
        $users = User::all();
        $depts = Department::all();
        $positions = Position::all();

        return view("admin.index");        
    }

    public function manager()
    {
        $users = User::all();
        $depts = Department::all();
        $positions = Position::all();

        return view("manager.index");        
    }

    public function edit()
    {
        return view("user.edit");        
    }

    public function update(Request $request)
    {

    }
}
