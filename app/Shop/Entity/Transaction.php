<?php

namespace App\Shop\Entity;

use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    
    // 資料表名稱
    protected $table = 'transaction';
    // 主鍵名稱
    protected $primaryKey = 'id';
    // 可以大量指定異動的欄位（Mass Assignment）
    protected $fillable = [
        "id",
        "user_id",
        "merchandise_id",
        "price",
        "buy_count",
        "total_price",
    ];
    //關聯(參數二是商品的ID參數三是交易紀錄的ID)
    public function Merchandise(){
        return $this->hasOne('App\Shop\Entity\Merchandise','id','merchandise_id');
    }
}
