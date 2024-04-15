<?php 

namespace App\BO\csv\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    
    protected $fillable = ['sku', 'name', 'description', 'brand'];
}
