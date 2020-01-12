<?php

namespace App\Shop\Entity;

use Illuminate\Database\Eloquent\Model;

class transactions extends Model
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
}
