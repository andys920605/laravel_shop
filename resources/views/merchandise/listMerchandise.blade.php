@extends('layout.master')

@section('title',$title)

@section('content')

    <div class ="container">
        <h1>{{$title}}</h1>
       
        @include('components.validationErrorMessage')

        <table>
            <tr>
                <th>名稱</th>
                <th>照片</th>
                <th>價格</th>
                <th>剩餘數量</th>
                <th>查看</th>
            </tr>
            @foreach($MerchandisePaginate as $Merchandise)
                <tr>
                    <td>
                        <a href="merchandise/{{$Merchandise->id}}">
                            {{$Merchandise->name}}
                        </a>
                    </td>
                    <td>
                        <a href="merchandise/{{$Merchandise->id}}">
                            <img src="{{$Merchandise->photo or '/assets/images/default-merchandise.png'}}"/>
                        </a>
                    </td>
                    <td>{{$Merchandise->price}}</td>
                    <td>{{$Merchandise->remain_count}}</td>
                    <td>
                        <a href="merchandise/{{$Merchandise->id}}">
                            點我查看
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
        {{$MerchandisePaginate->links()}}
    </div>
@endsection
        