<x-app-layout>
    <x-slot name="header">
        <h4>Update Department</h4>
    </x-slot>

    <br>
    <br>
    <div class="container">
        <form action="{{config('app.uPrefix') . '/department/update/' . $id}}" method="POST">
            @csrf
            Department name: <input type="text" value="{{$dept->department}}" placeholder="{{$dept->department}}" name="department" required/>
            <button class="btn btn-secondary">Update Department</button>
        </form>
    </div>

</x-app-layout>