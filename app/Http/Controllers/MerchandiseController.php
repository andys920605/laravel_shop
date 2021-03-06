<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop\Entity\Merchandise;
use App\Shop\Entity\Transaction;
use App\Shop\Entity\User;
use DB;
use Exception;
use Image;
use Validator;

class MerchandiseController extends Controller {
    //新增商品
    public function merchandiseCreateProcess(){

        //建立商品基本資訊
        $merchandise_data=[
            'status'             =>   'C',      //建立中
            'name'               =>   '',       //商品名稱
            'name_en'            =>   '',       //商品英文名稱
            'introduction'       =>   '',       //介紹
            'introduction_en'    =>   '',       //英文介紹
            'photo'              =>   'null',   //照片
            'price'              =>   '0',      //價格
            'remain_count'       =>   '0',      //剩餘數量
        ];

        $Merchandise =Merchandise::create($merchandise_data);

        return redirect ('/merchandise/'.$Merchandise->id . '/edit');


    }

    //商品編輯頁面
    public function merchandiseItemEditPage($merchandise_id){
        //撈取商品資料
        $Merchandise = Merchandise::findOrFail($merchandise_id);

        if(!is_null($Merchandise->photo)){
            $Merchandise->photo = url($Merchandise->photo);
        }


        $binding=[
            'title'=>'編輯商品',
            'Merchandise'=>$Merchandise,


        ];
        return view ('merchandise.editMerchandise',$binding);
    }


    public function merchandiseItemUpdateProcess($merchandise_id){
        //撈取商品資料
        $Merchandise = Merchandise::findOrFail($merchandise_id);
        $input=request()->all();
        
        //驗證規則
        $rules = [
            // 商品狀態
            'status'=> [
                'required',
                'in:C,S'
            ],
            // 商品名稱
            'name' => [
                'required',
                'max:5',
            ],
            // 商品英文名稱
            'name_en' => [
                'required',
                'max:80',
            ],
            // 商品介紹
            'introduction' => [
                'required',
                'max:2000',
            ],
            // 商品英文介紹
            'introduction_en' => [
                'required',
                'max:2000',
            ],
            // 商品照片
            'photo'=>[
                'file',         // 必須為檔案
                'image',        // 必須為圖片
                'max: 10240',   // 10 MB
            ],
            // 商品價格
            'price' => [
                'required',
                'integer',
                'min:0',
            ],
            // 商品剩餘數量
            'remain_count' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
        //驗證資料
        $validator = Validator::make($input,$rules);

        if($validator->fails()){
            return redirect('/merchandise/'.$Merchandise->id.'/edit')
            ->withErrors($validator)
            ->withInput();
        }


        //處理圖片
        if (isset($input['photo'])){
            // 有上傳圖片
            $photo = $input['photo'];
            // 檔案副檔名
            $file_extension = $photo->getClientOriginalExtension();
            // 產生自訂隨機檔案名稱
            $file_name = uniqid() . '.' . $file_extension;
            // 檔案相對路徑
            $file_relative_path = 'images/merchandise/' . $file_name;
            // 檔案存放目錄為對外公開 public 目錄下的相對位置
            $file_path = public_path($file_relative_path);
            // 裁切圖片
            $image = Image::make($photo)->fit(450, 300)->save($file_path);
            // 設定圖片檔案相對位置
            $input['photo'] = $file_relative_path;

        }

        $Merchandise->update($input);
        
        return redirect('/merchandise/'.$Merchandise->id.'/edit');


    }

    //商品清單檢視
    public function merchandiseManageListPage(){
        //每頁資料量
        $row_per_page=10;
        //撈取商品分頁資料
        $MerchandisePaginate = Merchandise::OrderBy('created_at','desc')
        ->paginate($row_per_page);

        //設定商品圖片網址
        foreach($MerchandisePaginate as &$Merchandise){
            if(!is_null($Merchandise->photo)){
                $Merchandise->photo = url($Merchandise->photo);
            }
        }


        $binding=[
            'title'=>'管理商品',
            'MerchandisePaginate'=>$MerchandisePaginate,

        ];
        return view('merchandise.manageMerchandise',$binding);


        
    }


    //商品單品檢視
    public function merchandiseItemPage($merchandise_id){    



        $Merchandise=Merchandise::findOrFail($merchandise_id);

        if(!is_null($Merchandise->photo)){
            $Merchandise->photo=url($Merchandise->photo);
        }

        $binding=[
            'title'=>'商品頁',
            'Merchandise'=>$Merchandise,

        ];
        return view('merchandise.showMerchandise',$binding);

    }
    

    public function merchandiseListPage(){  
        //每頁資料量
        $row_per_page = 10;
        
        $MerchandisePaginate=Merchandise::Orderby('updated_at','desc')
        ->where('status','S')
        ->paginate($row_per_page);

        //設定圖片
        foreach ($MerchandisePaginate as &$Merchandise){
            if (!is_null($Merchandise->photo)){
                $Merchandise->photo = url($Merchandise->photo);
            }
        }

        $binding = [
            'title'=>'商品列表',
            'MerchandisePaginate'=> $MerchandisePaginate,
        ];

        return view ('merchandise.listMerchandise',$binding);


    }  


    public function merchandiseItemBuyProcess($merchandise_id){
        //接收輸入的資料
        $input = request()->all();
        
        $rules = [
            'buy_count'=>[
            'required',
            'integer',
            'min:1',
            ],
        ];

        $validator = Validator::make($input,$rules);

        if($validator->fails()){
            return redirect('/merchandise/'.$Merchandise->id)
            ->withErrors($validator)
            ->withInput();
        }

        try{
            $user_id=session()->get('user_id');
            $User=User::findOrFail($user_id);

            //交易開始
            DB::beginTransaction();
            //取得商品資料
            $Merchandise=Merchandise::findOrFail($merchandise_id);
            //購買數量
            $buy_count=$input['buy_count'];
            //購買後剩餘數量
            $remain_count_after_buy = $Merchandise->remain_count-$buy_count;
            if ($remain_count_after_buy<0){
                throw new Exception('商品數量不足無法購買');
                
            }
            //紀錄購買後剩餘數量
            $Merchandise->remain_count =$remain_count_after_buy;
            $Merchandise->save();
            //總金額
            $total_price =$buy_count * $Merchandise->price;

            $transaction_data =[
                'user_id'          => $User->id,
                'merchandise_id'   =>$merchandise_id,
                'price'            =>$Merchandise->price,
                'buy_count'        =>$buy_count,
                'total_price'      =>$total_price,
            ];


            //建立交易資料
            Transaction::create($transaction_data);
            //交易結束
            DB::commit();

            //回傳購物訊息成功
            $message=[
                'msg'=>[
                    '購買成功',
                ],
            ];

            return redirect()
            ->to('/merchandise/'.$Merchandise->id)
            ->withErrors($message);

        }catch(Exception $exception){
            //恢復原先交易
            DB::rollBack();

            //回傳錯誤訊息
            $error_message =[
                'msg'=>[
                    $exception->getMessage(),
                ],
            ];
        }
        return redirect()
        ->back()
        ->withErrors($error_message)
        ->withInput();

    }
    
}
