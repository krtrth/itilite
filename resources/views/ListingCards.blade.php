<div class="row table-responsive">
    <table id="example" class="table" style="width:100%">
        <thead>
        <th></th>
        <th>Departure</th>
        <th>duration</th>
        <th>Arrival</th>
        <th>Price</th>
        </thead>
        <tbody id="table-body">
        @foreach($data as $flight)
            <tr>
                <td>{{$flight['name']}}<br> <small>({{$flight['code']}})</small></td>
                <td>{{$flight['departure']}}<br><small>{{$flight['from']}}</small></td>
                <td>{{$flight['duration']}}<br>({{$flight['stops']}} @if($flight['stops']>1)stops @else
                        stop @endif )</td>
                <td>{{$flight['arrival']}}<br><small>{{$flight['to']}}</small></td>
                <td>{{$flight['price']}}<br><a>Flight Details</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
