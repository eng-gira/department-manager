<x-app-layout>
    <x-slot name="header">
        <h4>IoT</h4>
    </x-slot>
    <div class="container">
        <table class = "table">
            <tr>
                <th>Key</th>
                <th>Value</th>
            </tr>
            @foreach($data as $d)
                <tr>
                    <td>{{$d->dataKey}}</td>
                    <td>{{$d->dataValue}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</x-app-layout>