<x-app-layout>
    <x-slot name="header">
        <h4>Update Position </h4>
    </x-slot>

    <br>
    <br>
    <div class="container">

        <form action="{{config('app.uPrefix') . '/position/update/' . $id}}" method="POST">
            @csrf
            Position name: <input type="text" value="{{$pos->position}}" placeholder="{{$pos->position}}" name="position" required/>
            <button class="btn btn-secondary">Update position</button>
        </form>
    </div>
</x-app-layout>