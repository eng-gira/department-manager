<x-app-layout>
    <x-slot name="header">
        <h4>IoT</h4>
    </x-slot>
    <div class="container">
        {{$data}}

        @foreach($data as $k=>$v)
            {{$k}}
            {{$v}}
        @endforeach
    </div>
</x-app-layout>