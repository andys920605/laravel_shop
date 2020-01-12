<?php

namespace App\Http\Controllers;
use Mail;
use Illuminate\Http\Request;   
use Validator; //驗證器
use Hash;//雜湊
use App\Shop\Entity\User;   // 使用者 Eloquent Model
use DB;

class UserAuthController extends Controller
{
    public function signUpPage(){
        //註冊頁面
        $binding=[
            'title'=> '註冊'
        ];
        return view('auth.signUp',$binding);
    }

    public function signUpProcess(){
        //接收表單
        $input =request()->all();

        //驗證規則
        $rules = [
            // 暱稱
            'nickname'=> [
                'required',
                'max:50',
            ],
            // Email
            'email'=> [
                'required',
                'max:150',
                'email',
            ],
            // 密碼
            'password' => [
                'required',
                'same:password_confirmation',
                'min:6',
            ],
            // 密碼驗證
            'password_confirmation' => [
                'required',
                'min:6',
            ],
            // 帳號類型
            'type' => [
                'required',
                'in:G,A'
            ],
        ];
        //驗證資料
        $validator = Validator::make($input,$rules);

        if ($validator->fails()){
            return redirect('/user/auth/sign-up')
                ->withErrors($validator)->withInput();
        }
        //密碼加密 
        $input['password'] = Hash::make($input['password']);
        //寫入資料庫
        $Users = User::create($input);
        //寄信註冊通知信
        $mail_binding = [
            
            'nickname'=>$input['nickname']
            
        ];

        Mail::send('email.inside',$mail_binding,function($mail)use ($input){
            //寄件人
            $mail->to($input['email']);
            //收件人
            $mail->from('andys920605@gmail.com');
            //郵件主旨
            $mail->subject('恭喜註冊shop laravel成功');

        });

        //重新導向到登入頁面

        return redirect('user/auth/sign-in');
        
       
    }


    public function signInPage(){
        //註冊頁面
        $binding=[
            'title'=> '登入'
        ];
        return view('auth.signIn',$binding);
    }




    public function signInProcess(){
        
        $input = request()->all();

        //驗證規則
        $rules = [
            // Email
            'email'=> [
                'required',
                'max:150',
                'email',
            ],
            // 密碼
            'password' => [
                'required',
                'min:6',
            ],
        ];
        
        
        $validator = Validator::make($input,$rules);

        if ($validator->fails()){
            return redirect('user/auth/sign-in')
                ->withErrors($validator)->withInput();
        }
        DB::enableQueryLog();

        $User = user::where('email',$input['email'])->firstOrFail();
        

        $is_password_crroect = Hash::check($input['password'],$User->password);

        if(!$is_password_crroect){
            $error_message = [
                'msg' => [
                    '密碼驗證錯誤',
                ],
                
            ];

        return redirect('user/auth/sign-in')
            ->withErrors($error_message)
            ->withInput();
            
        }

        //var_dump(DB::getQueryLog());
        //var_dump($User->id);
        //exit;

        //session 會員編號紀錄

        session()->put('user_id',$User->id);

        return redirect()->intended('user/auth/sign-in');

    }

    public function signOut(){
        //清除session
        session()->forget('user_id');

        return redirect('user/auth/sign-in');
    
    
    }







}

