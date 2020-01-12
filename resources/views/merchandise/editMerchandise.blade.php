@extends('layout.master')

@section('title',$title)

@section('content')

    <div class ="container">
        <h1>{{$title}}</h1>
        @include('components.socialButtons')
        @include('components.validationErrorMessage')

        <form action ="/merchandise/{{$Merchandise->id}}"
            method="post"
            enctype="multipart/form-data"
        
        >
        {{method_field('PUT')}}


            <label>
                商品狀態:
                <select name="status">
                    <option value="C"
                        @if(old('status',$Merchandise->status)=='C')
                        selected @endif
                    >
                        建立中
                    </option>
                    <option value="S"
                        @if(old('status',$Merchandise->status)=='S')
                        selected @endif
                    >
                        可販售
                    </option>
                </select>
            </label>

            <label>
                商品名稱:
                <input type="text" name="name" placeholder="商品名稱" value="{{old('name',$Merchandise->name)}}" >
                    
                    
                    
                    
                
            </label>

            <label>
                商品英文名稱:
                <input type="text"
                    name="name_en"
                    placeholder="商品英文名稱"
                    value="{{old('name_en',$Merchandise->name_en)}}"
                
                
                >

            </label>
                

            <label>
                商品介紹:
                <input type="text"
                    name="intorduction""
                    placeholder="商品介紹"
                    value="{{old('intorduction',$Merchandise->intorduction)}}"
                
                
                >


            </label>



            <label>
                商品英文介紹:
                <input type="text"
                    name="intorduction_en"
                    placeholder="商品英文介紹"
                    value="{{old('intorduction_en',$Merchandise->intorduction_en)}}"
                
                
                >
            </label>




            <label>
                商品照片:
                <input type="file""
                    name="photo"
                    placeholder="商品照片"
                    <img src="{{$Merchandise->photo or '/assets/images/default-merchandise.png'}}">
                
                
                >
            </label>




            <label>
                商品價格:
                <input type="text"
                    name="price"
                    placeholder="商品價格"
                    value="{{old('price',$Merchandise->price)}}"
                
                
                >
            </label>




            <label>
                商品剩餘數量:
                <input type="text"
                    name="remain_count""
                    placeholder="商品剩餘數量"
                    value="{{old('remain_count',$Merchandise->remain_count)}}"
                
                
                >
            </label>


            <button type ="submit" class="btn vtn-default">更新商品資訊
            </button>
            {{--CSRF欄位--}}
            {{csrf_field()}}
        </form>
    </div>
    
@endsection

