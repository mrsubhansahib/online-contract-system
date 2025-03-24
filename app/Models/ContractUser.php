<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'contract_id',
        'status'
    ];
    public function user(){
        return $this->hasOne( User::class,'id','user_id' );
    }
}
