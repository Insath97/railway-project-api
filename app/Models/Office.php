<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'office_name',
        'address',
        'phone_number',
        'email',
        'is_head_office',
        'delete_status'
    ];
}
