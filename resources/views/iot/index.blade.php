<x-app-layout>
    <x-slot name="header">
        <h4>IoT</h4>
    </x-slot>
    <div class="container">
        @if(is_array($data))
            <table class="table">
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                </tr>
                @foreach($data as $k=>$v)
                    <tr>
                        <td>
                            {{$k}}
                        </td>
                        <td>
                            {{$v}}
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            {{$data}}
        @endif
    </div>
</x-app-layout>