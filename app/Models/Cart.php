<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;


    protected $fillable = ['product_id','user_id','ip_address','quantity'];

    public static $addRules =[
        'product_id' => 'required| exists:products,id',
        // 'quantity' => 'required|integer'
    ];

    public function products(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
