<x-app-layout>
    <x-slot name="header">
        <h4>Create Position </h4>
    </x-slot>
    
    <br>
    <br>

    <form action="{{config('app.uPrefix') . '/position/store'}}" method="POST">
        @csrf
        Position name: <input type="text" name="position" required/>
        <button class="btn btn-primary">Create Position</button>
    </form>
</x-app-layout>