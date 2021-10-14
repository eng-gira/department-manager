
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
