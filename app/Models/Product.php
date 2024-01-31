<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";
    protected $primaryKey = "id";
    public $timestamps = true;
    public $increments = true;

    protected $fillable = ['name', 'description', 'price', 'quantity_in_stock', 'category_id', 'user_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'category_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'user_id', 'id');
    }
}
