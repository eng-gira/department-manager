<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    
    <br>
    <div class="container">
        @if($manages===1)
            @if($admin===1)
                <h4> Admin Controls </h4>
            @else
                <h4> Manager Controls </h4>
            @endif
            <a class="btn btn-primary" href="{{config('app.uPrefix') . '/listUsers'}}"> List Users </a>
            <a class="btn btn-secondary" href="{{config('app.uPrefix') . '/listManagers'}}"> List Managers </a>
            <br>
            <br>
        @endif

        <br>

        <a href="{{config('app.uPrefix') . '/department'}}">Available Departments</a>
        &nbsp;
        &nbsp;
        &nbsp;
        &nbsp;
        <a href="{{config('app.uPrefix') . '/position'}}">Available Positions</a>
        <br>
        <br>

        @if(isset($user_dept))
            @if(count($user_dept) > 0)
                <br>
                <h5> Users and Departments </h5>
                <table class="table">
                    <tr>
                        <th>User</th>
                        <th>Department</th>
                        <th>Position</th>
                        @if($admin===1)
                            <th></th>
                        @endif
                    </tr>
                    @foreach($user_dept as $ud)
                        <tr>
                            <td>
                                @if($userIdDataArr[$ud->user]->admin===1 && $admin===0 || $manages===0) 
                                    <p> {{$userIdDataArr[$ud->user]->name}}</p>
                                @else
                                    <a href="{{config('app.uPrefix') . '/user/edit/' . $ud->user}}">{{$userIdDataArr[$ud->user]->name}}</a>
                                @endif
                            </td>
                            <td><a href="{{config('app.uPrefix'). '/department/' . $ud->department}}"> {{$deptIdNameArr[$ud->department]}} </a></td>
                            <td>{{$posIdNameArr[$userIdDataArr[$ud->user]->position]}}</td>
                            <td>
                                @if($admin===1)
                                    <a class="btn btn-danger" href="{{config('app.uPrefix'). '/user/deleteUserDeptConnection/' . $ud->id}}">Remove from deparment</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    {{$user_dept->links()}}
                </table>
            @else
                <h4> No user-department-position relationship exists </h4>
            @endif
        @endif

        <hr>
        <br>
        <br>
        @if($auth!==0 && $admin===0)

            <h4> Account Settings </h4>
            <br>
            <!-- <form action="{{config('app.uPrefix') . '/user/updatePersonalInformation/' . $auth->id}}" method="POST">
                @csrf
                Name: <input type="text" value="{{$auth->name}}" placeholder="{{$auth->name}}" name="name"/>
                Email: <input type="text" value="{{$auth->email}}" placeholder="{{$auth->email}}" name="email"/>
                Password: <input type="password" name="password"/>
                <br>
                <br>
                <button class="btn btn-secondary">Update Personal Information</button>
            </form>

            <br>
            <hr>
            <br> -->

            <a class="btn btn-danger" href="{{config('app.uPrefix') . '/user/delete/' . $auth->id}}"> Delete Account </a>
        @endif
    </div>


</x-app-layout>


