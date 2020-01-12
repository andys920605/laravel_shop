
@if ($errors AND count($errors))

    <ul>
        @foreach($errors->all() as $err)
            <li>{{$err}}</li>
    </ul>    @endforeach
@endif