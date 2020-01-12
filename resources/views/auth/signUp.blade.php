@extends('layout.master')

@section('title',$title)

@section('content')

<div class ="container">
<h1>{{$title}}</h1>
@include('components.socialButtons')
@include('components.validationErrorMessage')

    <form action="/shop_laravel/public/user/auth/sign-up", method="post">
    
    <label>
            暱稱:
            <input type="text"
                name="nickname"
                placeholder="暱稱"
                value="{{old('nickname')}}"
            >   
        </label>
        
        <label>
            Email:
            <input type="text"
                name="email"
                placeholder="Email"
                value="{{old('email')}}"
            >   
        </label>

        <label>
            密碼:
            <input type="password"
                name="password"
                placeholder="密碼"
                value="{{old('password')}}"
            >
        </label>

        <label>
            確認密碼:
            <input type="password"
                name="password_confirmation"
                placeholder="密碼"
                value="{{old('password')}}"
            >
        </label>

        <label>
            帳號類型:
            <select name ="type">
                <option value="G">一般會員</option>   
                <option value="A">管理者</option> 
            </select>    
        </label>
        {!!csrf_field() !!}
    <button type ="submit">註冊</button>
    </form>
<div>
@endsection