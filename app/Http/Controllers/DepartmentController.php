<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Position;
use App\Models\Department;
use App\Models\User;

class DepartmentController extends Controller
{
    //
    public function index()
    {
        $depts = Department::all();
        
        return view("department.index");
    }

    public function create()
    {
        if(!$this->manages()) return redirect("/");
    }

    public function store(Request $request)
    {
        if(!$this->manages()) return redirect("/");
    }

    public function edit()
    {
        if(!$this->manages()) return redirect("/");
    }

    public function update(Request $request)
    {
        if(!$this->manages()) return redirect("/");
    }

    public function delete(Request $request)
    {
        if(!$this->manages()) return redirect("/");
    }

    private function manages()
    {
        return auth()->manager===1 || auth()->admin===1;
    }
}
