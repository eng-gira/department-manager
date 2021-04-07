<x-app-layout>
    <x-slot name="header">
        <h4>Create a Department</h4>
    </x-slot>

    <br>
    <br>

    <div class="container">

        <form action="{{config('app.uPrefix') . '/department/store'}}" method="POST">
            @csrf
            Department name: <input type="text" name="department" required/>
            <button class="btn btn-primary">Create Department</button>
        </form>
    </div>
</x-app-layout>