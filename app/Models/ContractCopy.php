<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractCopy extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'contract_id',
        'category_id',
        'title',
        'form_data',
        'added_by',
    ];
    protected $casts = [
        'form_data'=>'json'
    ];
}
