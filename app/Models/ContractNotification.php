<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'status',
        'user_id'
    ];
}
