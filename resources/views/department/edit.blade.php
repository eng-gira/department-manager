<x-app-layout>
    <x-slot name="header">
        <h4>Update Department</h4>
    </x-slot>


    <form action="{{config('app.uPrefix') . '/department/update/' . $id}}" method="POST">
        @csrf
        Department name: <input type="text" value="{{$dept->department}}" placeholder="{{$dept->department}}" name="department" required/>
        <button class="btn btn-secondary">Update Department</button>
    </form>

</x-app-layout>