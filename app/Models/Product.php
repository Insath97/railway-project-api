<?php

namespace App\Models;

use App\Models\UnitType;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_category_id',
        'sub_category_id',
        'unitType_id',
        'code',
        'name',
        'color',
        'quantity',
        'size',
        'low_stock_threshold',
        'delete_status',
    ];

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function unitType()
    {
        return $this->belongsTo(UnitType::class, 'unitType_id');
    }

    public function productStocks()
    {
        return $this->hasMany(ProductStock::class,'product_id');
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    /*   public function isLowStock(): bool
    {
        return $this->quantity < $this->low_stock_threshold;
    }

    public function checkAndTriggerLowStockAlert()
    {
        if ($this->isLowStock()) {
            Log::alert("Low stock alert for product: {$this->name} (ID: {$this->id}) - Quantity: {$this->quantity}");
        }
    } */
}
