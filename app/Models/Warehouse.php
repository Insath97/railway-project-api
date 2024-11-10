<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_id',
        'warehouse_code',
        'warehouse_name',
        'address',
        'phone_number',
        'email',
        'status',
        'delete_status'
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function productStocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}
