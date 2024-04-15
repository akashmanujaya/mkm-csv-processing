<?php 

namespace App\BO\csv\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a product entity within the database.
 *
 * This model is used to interact with the 'products' table in the database.
 * It manages data related to products, including their SKU, name, description, and brand.
 */
class Product extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * These attributes can be filled using mass assignment methods like create() and update().
     * 
     * @var array
     */
    protected $fillable = ['sku', 'name', 'description', 'brand'];
}
