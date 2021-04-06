<x-app-layout>
    <x-slot name="header">
        <h4>All Positions </h4>
    </x-slot>
    
    @if(isset($positions) && count($positions) > 0)
        <table class="table">
            <tr>
                <th>Position Id</th>
                <th>Position Name</th>
            </tr>
            @foreach($positions as $p)
                <tr>
                    <td>
                        {{$p->id}}
                    </td>
                    <td>
                        <p>{{$p->position}}</p>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    <a class="btn btn-primary" href="{{config('app.uPrefix') . '/position/create'}}">Create a Position</a>
</x-app-layout>