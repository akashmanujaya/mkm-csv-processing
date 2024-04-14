<?php 

namespace App\BO\csv\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products'; // Ensure this matches your actual table name

    protected $fillable = ['sku', 'name', 'description', 'brand'];
}
