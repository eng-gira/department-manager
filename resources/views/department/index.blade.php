<x-app-layout>
    <x-slot name="header">
        <h4>All Departments</h4>
    </x-slot>


    @if(isset($depts) && count($depts) > 0)
        <table class="table">
            <tr>
                <th>Department Id</th>
                <th>Department Name</th>
            </tr>
            @foreach($depts as $d)
                <tr>
                    <td>
                        {{$d->id}}
                    </td>
                    <td>
                        <a href="{{config('app.uPrefix') . '/department/' . $d->id}}">{{$d->department}}</a>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    <a class="btn btn-primary" href="{{config('app.uPrefix') . '/d/create'}}">Create a Department</a>
</x-app-layout>