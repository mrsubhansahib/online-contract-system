<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractTemplate extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title',
        'form_data',
        'category_id',
        'status',
    ];
    protected $casts = [
        'form_data'=>'json'
    ];
}
