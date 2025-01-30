<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
    'admin_id',
     'name',
     'quantity',
     'qty_type',
     'description',
     'price',
     'category_id',
     'sub_category',
     'image', 
 ];
}
