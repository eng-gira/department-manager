<x-app-layout>
    <x-slot name="header">
        <h4> All Users </h4>
        <h6><i><b>Note: users listed here do not include admin and managers.</b></i>
    </x-slot>
    <div class="container">
    
        @if(count($users) > 0)
            <table class="table">
                <tr>
                    <th>User</th>
                    <th></th>
                    @if($admin===1)
                        <th></th>
                        <th></th>
                    @endif
                </tr>

                @foreach($users as $u)
                    <tr>
                        <td>{{$u->name}}</td>
                        <td>
                            <a href="{{config('app.uPrefix') . '/user/edit/' . $u->id}}"> Edit </a>
                        </td>
                        @if($admin===1)
                            <td><a class="btn btn-warning" href="{{config('app.uPrefix') . '/user/setManager/' . $u->id}}">Set as Manager</a></td>
                            <td><a class="btn btn-danger" href="{{config('app.uPrefix') . '/user/delete/' . $u->id}}">Delete Account</a></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
</x-app-layout>