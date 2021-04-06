<x-app-layout>
    <x-slot name="header">
        <h4>Department: {{$dept->department}} </h4>
    </x-slot>


    <!-- Here goes the bg image -->
    <img src="{{$dept->background_image_url}}"/>
    <br>
    <br>
    <!-- Upload bg image form -->
    @if($manages===1)
        <form action="{{config('app.uPrefix') . '/department/saveBackgroundImage/' . $dept->id}}" method="POST">
            @csrf
            Background-Image Path: <input type="text" name="bg_image" required/>
            <button class="btn btn-secondary">Upload Image</button>
        </form>
    @endif

    <!-- Department data -->
    <h4> Created on: {{$createdOn}} </h4>
    <h4> Users: {{$countUsersInDept}} </h4>
</x-app-layout>