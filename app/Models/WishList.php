<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{

    protected $table = "wish_lists";
    use HasFactory;


    protected $fillable =['product_id','user_id','ip_address'];

    public static $addRules =[
        'product_id' => 'required| exists:products,id',
    ];
}
