<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title',
        'category_id',
        'form_data',
        'status',
        'added_by',
        'download_status'
    ];
    protected $casts = [
        'form_data'=>'json'
    ];
    public function contract_users(){
        return $this->hasMany( ContractUser::class,'contract_id','id' );
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'contract_users', 'contract_id', 'user_id');
    }
}
