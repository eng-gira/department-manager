<x-app-layout>
    <x-slot name="header">
        <h4>Department: {{$dept->department}} </h4>

        <!-- Department data -->
        <h6> Created on: {{$createdOn}} </h6>
        <h6> Users: {{$countUsersInDept}} </h6>

        <!-- Management Controls -->
        @if($manages===1)
            <h6><a href="{{config('app.uPrefix') . '/department/edit/' . $dept->id}}"> Edit </a></h6>
        @endif
    </x-slot>


    <!-- Here goes the bg image -->
    <div class="container">
        <br>
        <br>
        <!-- Upload bg image form -->
        @if($manages==1)
            <form action="{{config('app.uPrefix') . '/department/saveBackgroundImage/' . $dept->id}}" method="POST"
                enctype="multipart/form-data">
                @csrf
                Choose image (Max Size = 5MB): <input type="file" max-size="20" name="bg_image" required/>
                <br>
                <br>

                <button class="btn btn-secondary">Upload Image</button>
            </form>
        @endif
        <br>
        <img alt="Department's Background Image Goes Here" width="500" height="450" src="{{$dept->background_image_url}}"/>
        <br>
        <br>
    </div>
</x-app-layout>
