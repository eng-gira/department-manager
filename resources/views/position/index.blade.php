<x-app-layout>
    <x-slot name="header">
        <h4>All Positions </h4>
    </x-slot>
    
    <div class="container">
        <br>
        <br>
        @if($manages===1)
            <a class="btn btn-primary" href="{{config('app.uPrefix') . '/position/create'}}">Create a Position</a>
        @endif

        @if(isset($positions) && count($positions) > 0)
        <br>
        <br>
            <table class="table">
                <tr>
                    <th>Position Id</th>
                    <th>Position Name</th>

                    @if($admin===1)
                        <th></th>
                    @endif
                </tr>
                @foreach($positions as $p)
                    <tr>
                        <td>
                            {{$p->id}}
                        </td>
                        <td>
                            @if($manages)
                                <a href="{{config('app.uPrefix') . '/position/edit/'. $p->id}}">{{$p->position}}</a>                        
                            @else
                                <p>{{$p->position}}</p>
                            @endif
                        </td>

                        @if($admin===1)
                            <td>
                                <a class="btn btn-danger" href="{{config('app.uPrefix') . '/position/delete/'. $p->id}}">Delete</a>                   
                            </td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
</x-app-layout>