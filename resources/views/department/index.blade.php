<x-app-layout>
    <x-slot name="header">
        <h4>All Departments</h4>
    </x-slot>


    <div class="container">
        <br>
        <br>
        @if($manages===1)
            <a class="btn btn-primary" href="{{config('app.uPrefix') . '/d/create'}}">Create a Department</a>
        @endif

        @if(isset($depts) && count($depts) > 0)
        <br>
        <br>
            <table class="table">
                <tr>
                    <th>Department Id</th>
                    <th>Department Name</th>
                    @if($admin===1)
                        <th></th>
                    @endif
                </tr>
                @foreach($depts as $d)
                    <tr>
                        <td>
                            {{$d->id}}
                        </td>
                        <td>
                            <a href="{{config('app.uPrefix') . '/department/' . $d->id}}">{{$d->department}}</a>
                        </td>
                        @if($admin===1)
                            <td>
                                <a class="btn btn-danger" href="{{config('app.uPrefix') . '/department/delete/'. $d->id}}">Delete</a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
</x-app-layout>