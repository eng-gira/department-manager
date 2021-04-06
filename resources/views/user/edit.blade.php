<x-app-layout>
    <x-slot name="header">
        <h4> New Department / Update Position for user {{$user->name}}</h4>
    </x-slot>
    
    <br>
    <div class="container">
        <form action="{{config('app.uPrefix') . '/user/update/' . $id}}" 
            method="POST">
            @csrf

            <h6>
                <b>
                    <i>
                        Note: if the department submitted is different than 
                        all the departments the user is in, the user is added 
                        to the new department. <br> In all cases, if the position 
                        submitted is different than the user's current, 
                        the user's position is updated in all departments.
                    </i>
                </b>
            </h6>

            <br>
            <label class="input-group-text" for="departments"> Departments </label>
            <select name="dept" class="custom-select" id="departments">
                @foreach($depts as $d)
                    <option value="{{$d->id}}"> {{$d->department}} </option>
                @endforeach
            </select>

            <br>
            <br>

            <label class="input-group-text" for="positions"> Positions </label>
            <select name="pos" class="custom-select" id="positions">
                @foreach($positions as $p)
                    @if($user->position === $p->id)
                        <option value="{{$p->id}}" selected> {{$p->position}} </option>
                    @else
                        <option value="{{$p->id}}"> {{$p->position}} </option>
                    @endif
                @endforeach
            </select>

            <br>
            <br>

            <button class="btn btn-secondary">Update User</button>        
        </form>
    </div>
</x-app-layout>