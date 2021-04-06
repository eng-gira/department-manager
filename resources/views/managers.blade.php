<x-app-layout>
    <x-slot name="header">
        <h4> All Managers </h4>
    </x-slot>
    <div class="container">
        @if(count($managers) > 0)
            <table class="table">
                <tr>
                    <th>Manager</th>
                    <th></th>
                    @if($admin===1)
                        <th></th>
                        <th></th>
                    @endif
                </tr>

                @foreach($managers as $m)
                    <tr>
                        <td>{{$m->name}}</td>
                        <td>
                            <a href="{{config('app.uPrefix') . '/user/edit/' . $m->id}}"> Edit </a>
                        </td>
                        @if($admin===1)
                            <td><a class="btn btn-warning" href="{{config('app.uPrefix') . '/user/unsetManager/' . $m->id}}">Unset Manager</a></td>
                            <td><a class="btn btn-danger" href="{{config('app.uPrefix') . '/user/delete/' . $m->id}}">Delete Account</a></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
</x-app-layout>