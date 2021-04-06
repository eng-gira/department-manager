<x-app-layout>
    <x-slot name="header">
        <h4>Department: {{$dept->department}} </h4>

        <!-- Department data -->
        <h6> Created on: {{$createdOn}} </h6>
        <h6> Users: {{$countUsersInDept}} </h6>
    </x-slot>


    <!-- Here goes the bg image -->
    <img src="{{$dept->background_image_url}}"/>
    <br>
    <br>
    <!-- Upload bg image form -->
    @if($manages==1)
        <form action="{{config('app.uPrefix') . '/department/saveBackgroundImage/' . $dept->id}}" method="POST"
            enctype="multipart/form-data">
            @csrf
            Choose image: <input type="file" name="bg_image" required/>
            <br>
            <br>

            <button class="btn btn-secondary">Upload Image</button>
        </form>
    @endif
</x-app-layout>